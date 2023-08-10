<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Modules\Activities\Models\ActivityType;
use Modules\Billable\Models\Product;
use Modules\Calls\Models\CallOutcome;
use Modules\Contacts\Models\Company;
use Modules\Contacts\Models\Contact;
use Modules\Contacts\Models\Source;
use Modules\Core\Environment;
use Modules\Core\Models\Country;
use Modules\Deals\Database\Seeders\LostReasonSeeder;
use Modules\Deals\Models\Deal;
use Modules\Deals\Models\Pipeline;
use Modules\Users\Models\User;

class DemoDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Environment::capture([
            '_server_ip' => '',
            '_prev_app_url' => null,
        ]);

        settings(['company_country_id' => $this->demoCountryId()]);

        $this->call(LostReasonSeeder::class);

        $users = User::factory(5)->create(
            ['super_admin' => collect([0, 1])->random()]
        );

        $pipeline = Pipeline::first();

        $users->each(function ($user, $index) use ($pipeline) {
            // For activity log causer and created_by
            Auth::loginUsingId($user->id);

            Product::factory()->for($user, 'creator')->create([
                'name' => $this->productNames()[$index],
            ]);

            Company::factory(5)->for($user)->for($user, 'creator')
                ->hasPhones()
                ->has(
                    Contact::factory()->for($user)->for($user, 'creator')
                        ->hasPhones()
                        ->has(Deal::factory()->for($pipeline)->for($user)->for($user, 'creator'))
                        ->for(Source::inRandomOrder()->first())
                        ->count(collect([0, 1, 2])->random())
                )
                ->for(Source::inRandomOrder()->first())
                ->has(Deal::factory()->for($pipeline)->for($user)->for($user, 'creator'))
                ->create()
                ->each(function ($company) use ($user) {
                    $this->seedCommonRelations($company, $user);

                    $company->deals->each(fn ($deal) => $this->seedCommonRelations($deal, $user));

                    $company->contacts->each(function ($contact) use ($user) {
                        $this->seedCommonRelations($contact, $user);

                        $contact->deals()->get()->each(fn ($deal) => $this->seedCommonRelations($deal, $user));
                    });
                });
        });

        $this->markRandomDealsAsLostOrWon();
        $this->setFirstUserCommonLogin();
    }

    /**
     * Set the first user common login details.
     */
    protected function setFirstUserCommonLogin(): void
    {
        $userAdmin = User::find(1);
        $userAdmin->name = 'Admin';
        $userAdmin->email = 'admin@test.com';
        $userAdmin->password = bcrypt('123123');
        $userAdmin->remember_token = Str::random(10);
        $userAdmin->timezone = 'Europe/Berlin';
        $userAdmin->access_api = true;
        $userAdmin->super_admin = true;
        $userAdmin->save();
    }

    /**
     * Seed the resources common relations.
     */
    protected function seedCommonRelations($model, $user): void
    {
        $model->changelog()->update(
            $this->changelogAttributes($user)
        );

        $model->notes()->save(\Modules\Notes\Models\Note::factory()->for($user)->make());

        $model->calls()->save(
            \Modules\Calls\Models\Call::factory()
                ->for(CallOutcome::inRandomOrder()->first(), 'outcome')
                ->for($user)
                ->make()
        );

        $activity = $model->activities()->save(
            \Modules\Activities\Models\Activity::factory()->for($user)
                ->for($user, 'creator')
                ->for(ActivityType::inRandomOrder()->first(), 'type')
                ->make(['note' => null])
        );

        //  Attempted to lazy load [guestable] on model [Modules\Activities\Models] but lazy loading is disabled.
        $activity->load('guests.guestable');

        $activity->addGuest($user);

        if ($model instanceof \Modules\Contacts\Models\Contact) {
            $activity->addGuest($model);
        } else {
            if ($contact = $model->contacts?->first()) {
                $activity->addGuest($contact);
            }
        }
    }

    /**
     * Get the country id for the demo.
     */
    protected function demoCountryId(): int
    {
        return Country::where('name', 'United States')->first()->getKey();
    }

    /**
     * Activity overwrite.
     */
    protected function changelogAttributes($user): array
    {
        return [
            'causer_id' => $user->id,
            'causer_type' => $user::class,
            'causer_name' => $user->name,
        ];
    }

    /**
     * Mark random deals as won and lost.
     */
    protected function markRandomDealsAsLostOrWon(): void
    {
        Deal::take(5)->latest()->inRandomOrder()->get()->each->markAsLost('Probable cause');
        Deal::take(5)->oldest()->inRandomOrder()->get()->each->markAsWon();
    }

    /**
     * Get the available dummy data product names.
     */
    protected function productNames(): array
    {
        return ['SEO Optimization', 'Web Design', 'Consultant Services', 'MacBook Pro', 'Marketing Services'];
    }
}
