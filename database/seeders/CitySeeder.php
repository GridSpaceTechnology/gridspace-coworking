<?php

namespace Database\Seeders;

use App\Models\City;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CitySeeder extends Seeder
{
    /**
     * Seed all Nigerian states and major cities.
     */
    public function run(): void
    {
        $citiesByState = [
            'Abia' => [
                'Umuahia', 'Aba', 'Arochukwu', 'Ohafia', 'Bende', 'Isuikwuato',
            ],
            'Adamawa' => [
                'Yola', 'Mubi', 'Jimeta', 'Numan', 'Ganye', 'Gombi', 'Michika', 'Hong',
            ],
            'Akwa Ibom' => [
                'Uyo', 'Eket', 'Ikot Ekpene', 'Oron', 'Abak', 'Ikot Abasi', 'Etinan', 'Ibeno',
            ],
            'Anambra' => [
                'Awka', 'Onitsha', 'Nnewi', 'Ekwulobia', 'Ihiala', 'Aguata', 'Ogidi', 'Obosi', 'Nkpor',
            ],
            'Bauchi' => [
                'Bauchi', 'Azare', 'Jama\'are', 'Misau', 'Ningi', 'Dass', 'Tafawa Balewa', 'Katagum',
            ],
            'Bayelsa' => [
                'Yenagoa', 'Brass', 'Ogbia', 'Sagbama', 'Nembe', 'Southern Ijaw', 'Ekeremor',
            ],
            'Benue' => [
                'Makurdi', 'Gboko', 'Otukpo', 'Katsina-Ala', 'Vandeikya', 'Adikpo', 'Oju', 'Okpoga',
            ],
            'Borno' => [
                'Maiduguri', 'Biu', 'Bama', 'Dikwa', 'Gwoza', 'Monguno', 'Konduga', 'Askira',
            ],
            'Cross River' => [
                'Calabar', 'Ikom', 'Ogoja', 'Ugep', 'Obudu', 'Akamkpa', 'Obubra', 'Odukpani',
            ],
            'Delta' => [
                'Asaba', 'Warri', 'Sapele', 'Ughelli', 'Agbor', 'Kwale', 'Oleh', 'Ozoro', 'Abraka', 'Effurun',
            ],
            'Ebonyi' => [
                'Abakaliki', 'Afikpo', 'Onueke', 'Ezza', 'Ishieke', 'Edda', 'Ikwo',
            ],
            'Edo' => [
                'Benin City', 'Auchi', 'Ekpoma', 'Uromi', 'Irrua', 'Sabongida-Ora', 'Igarra', 'Ubiaja',
            ],
            'Ekiti' => [
                'Ado-Ekiti', 'Ikere-Ekiti', 'Ijero-Ekiti', 'Oye-Ekiti', 'Ikole-Ekiti', 'Emure-Ekiti', 'Ise-Ekiti', 'Aramoko-Ekiti',
            ],
            'Enugu' => [
                'Enugu', 'Nsukka', 'Agbani', 'Awgu', 'Oji River', 'Udi', 'Ninth Mile', 'Emene',
            ],
            'FCT' => [
                'Abuja', 'Gwagwalada', 'Kuje', 'Bwari', 'Kwali', 'Abaji', 'Kubwa', 'Lugbe', 'Nyanya', 'Karu', 'Jabi', 'Maitama', 'Wuse', 'Garki', 'Asokoro',
            ],
            'Gombe' => [
                'Gombe', 'Kaltungo', 'Billiri', 'Bajoga', 'Dukku', 'Kumo', 'Deba',
            ],
            'Imo' => [
                'Owerri', 'Orlu', 'Okigwe', 'Mbaise', 'Oguta', 'Nkwerre', 'Mbano', 'Ideato',
            ],
            'Jigawa' => [
                'Dutse', 'Hadejia', 'Kazaure', 'Gumel', 'Birnin Kudu', 'Ringim', 'Kafin Hausa',
            ],
            'Kaduna' => [
                'Kaduna', 'Zaria', 'Kafanchan', 'Kagoro', 'Saminaka', 'Birnin Gwari', 'Makarfi', 'Soba', 'Giwa',
            ],
            'Kano' => [
                'Kano', 'Wudil', 'Gaya', 'Rano', 'Bichi', 'Gwarzo', 'Dambatta', 'Kura', 'Dawakin Tofa',
            ],
            'Katsina' => [
                'Katsina', 'Daura', 'Funtua', 'Malumfashi', 'Kankia', 'Dutsin-Ma', 'Mani', 'Bakori',
            ],
            'Kebbi' => [
                'Birnin Kebbi', 'Argungu', 'Yauri', 'Zuru', 'Jega', 'Koko', 'Bagudo',
            ],
            'Kogi' => [
                'Lokoja', 'Okene', 'Idah', 'Kabba', 'Ankpa', 'Anyigba', 'Dekina', 'Ajaokuta',
            ],
            'Kwara' => [
                'Ilorin', 'Offa', 'Omu-Aran', 'Jebba', 'Lafiagi', 'Patigi', 'Kaiama', 'Share',
            ],
            'Lagos' => [
                'Lagos Island', 'Ikeja', 'Ikorodu', 'Lekki', 'Victoria Island', 'Ikoyi', 'Epe', 'Ajah', 'Badagry',
                'Surulere', 'Yaba', 'Agege', 'Alimosho', 'Mushin', 'Oshodi', 'Apapa', 'Festac', 'Maryland',
                'Gbagada', 'Magodo', 'Ojota', 'Ketu', 'Ilupeju', 'Isolo', 'Egbeda', 'Ipaja', 'Ayobo',
            ],
            'Nasarawa' => [
                'Lafia', 'Keffi', 'Akwanga', 'Nasarawa', 'Karu', 'Doma', 'Nasarawa Eggon', 'Toto',
            ],
            'Niger' => [
                'Minna', 'Bida', 'Suleja', 'Kontagora', 'New Bussa', 'Lapai', 'Agaie', 'Mokwa',
            ],
            'Ogun' => [
                'Abeokuta', 'Ijebu-Ode', 'Sagamu', 'Ilaro', 'Ota', 'Ifo', 'Sango-Ota', 'Mowe', 'Arepo', 'Agbara',
            ],
            'Ondo' => [
                'Akure', 'Ondo', 'Owo', 'Ikare', 'Okitipupa', 'Ile-Oluji', 'Idanre', 'Ore',
            ],
            'Osun' => [
                'Osogbo', 'Ile-Ife', 'Ilesa', 'Ede', 'Iwo', 'Ikire', 'Ila-Orangun', 'Ejigbo', 'Ikirun',
            ],
            'Oyo' => [
                'Ibadan', 'Oyo', 'Ogbomoso', 'Iseyin', 'Saki', 'Eruwa', 'Igboho', 'Kishi', 'Lalupon',
            ],
            'Plateau' => [
                'Jos', 'Bukuru', 'Pankshin', 'Shendam', 'Langtang', 'Barkin Ladi', 'Mangu', 'Bokkos',
            ],
            'Rivers' => [
                'Port Harcourt', 'Obio-Akpor', 'Bonny', 'Eleme', 'Okrika', 'Ahoada', 'Bori', 'Degema', 'Omoku', 'Rumuokoro',
            ],
            'Sokoto' => [
                'Sokoto', 'Tambuwal', 'Wurno', 'Illela', 'Gwadabawa', 'Bodinga', 'Yabo',
            ],
            'Taraba' => [
                'Jalingo', 'Wukari', 'Bali', 'Takum', 'Gembu', 'Serti', 'Ibi', 'Zing',
            ],
            'Yobe' => [
                'Damaturu', 'Potiskum', 'Gashua', 'Nguru', 'Geidam', 'Buni Yadi', 'Fika',
            ],
            'Zamfara' => [
                'Gusau', 'Kaura Namoda', 'Talata Mafara', 'Anka', 'Maru', 'Bakura', 'Tsafe',
            ],
        ];

        $seededIds = [];

        foreach ($citiesByState as $state => $cities) {
            foreach (array_values(array_unique($cities)) as $cityName) {
                $city = City::query()
                    ->where('name', $cityName)
                    ->where('state', $state)
                    ->first();

                if (! $city) {
                    $city = City::query()
                        ->where('name', $cityName)
                        ->where(function ($query) {
                            $query->whereNull('state')->orWhere('state', '');
                        })
                        ->first();
                }

                $slug = $this->uniqueSlug($cityName, $state, $city?->id);

                if (! $city) {
                    $city = City::query()->where('slug', $slug)->first();
                }

                if ($city) {
                    $city->update([
                        'name' => $cityName,
                        'state' => $state,
                        'slug' => $this->uniqueSlug($cityName, $state, $city->id),
                    ]);
                } else {
                    $city = City::create([
                        'name' => $cityName,
                        'state' => $state,
                        'slug' => $slug,
                    ]);
                }

                $seededIds[] = $city->id;
            }
        }

        // Remove outdated city rows that are not used by listings.
        City::query()
            ->whereNotIn('id', $seededIds)
            ->whereDoesntHave('listings')
            ->delete();
    }

    private function uniqueSlug(string $cityName, string $state, ?int $ignoreId = null): string
    {
        $base = Str::slug($cityName . '-' . $state);
        $slug = $base;
        $counter = 1;

        while (
            City::query()
                ->when($ignoreId, fn ($query) => $query->where('id', '!=', $ignoreId))
                ->where('slug', $slug)
                ->exists()
        ) {
            $slug = $base . '-' . $counter++;
        }

        return $slug;
    }
}
