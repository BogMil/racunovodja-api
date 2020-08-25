<?php

use App\Opstina;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OpstinaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            [
                'sifra' => '201',
                'naziv' => 'Ada',
            ],
            [
                'sifra' => '001',
                'naziv' => 'Aleksandrovac',
            ],
            [
                'sifra' => '002',
                'naziv' => 'Aleksinac',
            ],
            [
                'sifra' => '202',
                'naziv' => 'Alibunar',
            ],
            [
                'sifra' => '203',
                'naziv' => 'Apatin',
            ],
            [
                'sifra' => '003',
                'naziv' => 'Aranđelovac',
            ],
            [
                'sifra' => '004',
                'naziv' => 'Arilje',
            ],
            [
                'sifra' => '006',
                'naziv' => 'Babušnica',
            ],
            [
                'sifra' => '007',
                'naziv' => 'Bajina Bašta',
            ],
            [
                'sifra' => '010',
                'naziv' => 'Barajevo',
            ],
            [
                'sifra' => '008',
                'naziv' => 'Batočina',
            ],
            [
                'sifra' => '204',
                'naziv' => 'Bač',
            ],
            [
                'sifra' => '205',
                'naziv' => 'Bačka Palanka',
            ],
            [
                'sifra' => '206',
                'naziv' => 'Bačka Topola',
            ],
            [
                'sifra' => '207',
                'naziv' => 'Bački Petrovac',
            ],
            [
                'sifra' => '009',
                'naziv' => 'Bela Palanka',
            ],
            [
                'sifra' => '209',
                'naziv' => 'Bela Crkva',
            ],
            [
                'sifra' => '210',
                'naziv' => 'Beočin',
            ],
            [
                'sifra' => '208',
                'naziv' => 'Bečej',
            ],
            [
                'sifra' => '023',
                'naziv' => 'Blace',
            ],
            [
                'sifra' => '024',
                'naziv' => 'Bogatić',
            ],
            [
                'sifra' => '025',
                'naziv' => 'Bojnik',
            ],
            [
                'sifra' => '026',
                'naziv' => 'Boljevac',
            ],
            [
                'sifra' => '027',
                'naziv' => 'Bor',
            ],
            [
                'sifra' => '028',
                'naziv' => 'Bosilegrad',
            ],
            [
                'sifra' => '029',
                'naziv' => 'Brus',
            ],
            [
                'sifra' => '030',
                'naziv' => 'Bujanovac',
            ],
            [
                'sifra' => '107',
                'naziv' => 'Valjevo',
            ],
            [
                'sifra' => '108',
                'naziv' => 'Varvarin',
            ],
            [
                'sifra' => '109',
                'naziv' => 'Velika Plana',
            ],
            [
                'sifra' => '110',
                'naziv' => 'Veliko Gradište',
            ],
            [
                'sifra' => '321',
                'naziv' => 'Vitina',
            ],
            [
                'sifra' => '112',
                'naziv' => 'Vladimirci',
            ],
            [
                'sifra' => '111',
                'naziv' => 'Vladičin Han',
            ],
            [
                'sifra' => '113',
                'naziv' => 'Vlasotince',
            ],
            [
                'sifra' => '019',
                'naziv' => 'Voždovac',
            ],
            [
                'sifra' => '114',
                'naziv' => 'Vranje',
            ],
            [
                'sifra' => '020',
                'naziv' => 'Vračar',
            ],
            [
                'sifra' => '240',
                'naziv' => 'Vrbas',
            ],
            [
                'sifra' => '115',
                'naziv' => 'Vrnjačka Banja',
            ],
            [
                'sifra' => '241',
                'naziv' => 'Vršac',
            ],
            [
                'sifra' => '322',
                'naziv' => 'Vučitrn',
            ],
            [
                'sifra' => '039',
                'naziv' => 'Gadžin Han',
            ],
            [
                'sifra' => '304',
                'naziv' => 'Glogovac',
            ],
            [
                'sifra' => '305',
                'naziv' => 'Gnjilane',
            ],
            [
                'sifra' => '040',
                'naziv' => 'Golubac',
            ],
            [
                'sifra' => '331',
                'naziv' => 'Gora',
            ],
            [
                'sifra' => '041',
                'naziv' => 'Gornji Milanovac',
            ],
            [
                'sifra' => '012',
                'naziv' => 'Grocka',
            ],
            [
                'sifra' => '036',
                'naziv' => 'Despotovac',
            ],
            [
                'sifra' => '301',
                'naziv' => 'Dečani',
            ],
            [
                'sifra' => '037',
                'naziv' => 'Dimitrovgrad',
            ],
            [
                'sifra' => '038',
                'naziv' => 'Doljevac',
            ],
            [
                'sifra' => '303',
                'naziv' => 'Đakovica',
            ],
            [
                'sifra' => '243',
                'naziv' => 'Žabalj',
            ],
            [
                'sifra' => '117',
                'naziv' => 'Žabari',
            ],
            [
                'sifra' => '118',
                'naziv' => 'Žagubica',
            ],
            [
                'sifra' => '244',
                'naziv' => 'Žitište',
            ],
            [
                'sifra' => '119',
                'naziv' => 'Žitorađa',
            ],
            [
                'sifra' => '116',
                'naziv' => 'Zaječar',
            ],
            [
                'sifra' => '022',
                'naziv' => 'Zvezdara',
            ],
            [
                'sifra' => '330',
                'naziv' => 'Zvečan',
            ],
            [
                'sifra' => '021',
                'naziv' => 'Zemun',
            ],
            [
                'sifra' => '242',
                'naziv' => 'Zrenjanin',
            ],
            [
                'sifra' => '324',
                'naziv' => 'Zubin Potok',
            ],
            [
                'sifra' => '042',
                'naziv' => 'Ivanjica',
            ],
            [
                'sifra' => '212',
                'naziv' => 'Inđija',
            ],
            [
                'sifra' => '213',
                'naziv' => 'Irig',
            ],
            [
                'sifra' => '306',
                'naziv' => 'Istok',
            ],
            [
                'sifra' => '096',
                'naziv' => 'Jagodina',
            ],
            [
                'sifra' => '214',
                'naziv' => 'Kanjiža',
            ],
            [
                'sifra' => '307',
                'naziv' => 'Kačanik',
            ],
            [
                'sifra' => '215',
                'naziv' => 'Kikinda',
            ],
            [
                'sifra' => '043',
                'naziv' => 'Kladovo',
            ],
            [
                'sifra' => '308',
                'naziv' => 'Klina',
            ],
            [
                'sifra' => '044',
                'naziv' => 'Knić',
            ],
            [
                'sifra' => '045',
                'naziv' => 'Knjaževac',
            ],
            [
                'sifra' => '216',
                'naziv' => 'Kovačica',
            ],
            [
                'sifra' => '217',
                'naziv' => 'Kovin',
            ],
            [
                'sifra' => '048',
                'naziv' => 'Kosjerić',
            ],
            [
                'sifra' => '328',
                'naziv' => 'Kosovo Polje',
            ],
            [
                'sifra' => '309',
                'naziv' => 'Kosovska Kamenica',
            ],
            [
                'sifra' => '310',
                'naziv' => 'Kosovska Mitrovica',
            ],
            [
                'sifra' => '046',
                'naziv' => 'Koceljeva',
            ],
            [
                'sifra' => '049',
                'naziv' => 'Kragujevac',
            ],
            [
                'sifra' => '050',
                'naziv' => 'Kraljevo',
            ],
            [
                'sifra' => '051',
                'naziv' => 'Krupanj',
            ],
            [
                'sifra' => '052',
                'naziv' => 'Kruševac',
            ],
            [
                'sifra' => '218',
                'naziv' => 'Kula',
            ],
            [
                'sifra' => '054',
                'naziv' => 'Kuršumlija',
            ],
            [
                'sifra' => '053',
                'naziv' => 'Kučevo',
            ],
            [
                'sifra' => '056',
                'naziv' => 'Lazarevac',
            ],
            [
                'sifra' => '055',
                'naziv' => 'Lajkovac',
            ],
            [
                'sifra' => '121',
                'naziv' => 'Lapovo',
            ],
            [
                'sifra' => '057',
                'naziv' => 'Lebane',
            ],
            [
                'sifra' => '311',
                'naziv' => 'Leposavić',
            ],
            [
                'sifra' => '058',
                'naziv' => 'Leskovac',
            ],
            [
                'sifra' => '312',
                'naziv' => 'Lipljan',
            ],
            [
                'sifra' => '059',
                'naziv' => 'Loznica',
            ],
            [
                'sifra' => '060',
                'naziv' => 'Lučani',
            ],
            [
                'sifra' => '061',
                'naziv' => 'Ljig',
            ],
            [
                'sifra' => '062',
                'naziv' => 'Ljubovija',
            ],
            [
                'sifra' => '063',
                'naziv' => 'Majdanpek',
            ],
            [
                'sifra' => '065',
                'naziv' => 'Mali Zvornik',
            ],
            [
                'sifra' => '219',
                'naziv' => 'Mali Iđoš',
            ],
            [
                'sifra' => '066',
                'naziv' => 'Malo Crniće',
            ],
            [
                'sifra' => '067',
                'naziv' => 'Medveđa',
            ],
            [
                'sifra' => '128',
                'naziv' => 'Mediana',
            ],
            [
                'sifra' => '068',
                'naziv' => 'Merošina',
            ],
            [
                'sifra' => '069',
                'naziv' => 'Mionica',
            ],
            [
                'sifra' => '070',
                'naziv' => 'Mladenovac',
            ],
            [
                'sifra' => '072',
                'naziv' => 'Negotin',
            ],
            [
                'sifra' => '122',
                'naziv' => 'Niška Banja',
            ],
            [
                'sifra' => '074',
                'naziv' => 'Nova Varoš',
            ],
            [
                'sifra' => '220',
                'naziv' => 'Nova Crnja',
            ],
            [
                'sifra' => '013',
                'naziv' => 'Novi Beograd',
            ],
            [
                'sifra' => '221',
                'naziv' => 'Novi Bečej',
            ],
            [
                'sifra' => '222',
                'naziv' => 'Novi Kneževac',
            ],
            [
                'sifra' => '075',
                'naziv' => 'Novi Pazar',
            ],
            [
                'sifra' => '223',
                'naziv' => 'Novi Sad',
            ],
            [
                'sifra' => '329',
                'naziv' => 'Novo Brdo',
            ],
            [
                'sifra' => '327',
                'naziv' => 'Obilić',
            ],
            [
                'sifra' => '014',
                'naziv' => 'Obrenovac',
            ],
            [
                'sifra' => '225',
                'naziv' => 'Opovo',
            ],
            [
                'sifra' => '313',
                'naziv' => 'Orahovac',
            ],
            [
                'sifra' => '076',
                'naziv' => 'Osečina',
            ],
            [
                'sifra' => '224',
                'naziv' => 'Odžaci',
            ],
            [
                'sifra' => '015',
                'naziv' => 'Palilula',
            ],
            [
                'sifra' => '127',
                'naziv' => 'Palilula (Niš)',
            ],
            [
                'sifra' => '125',
                'naziv' => 'Pantelej',
            ],
            [
                'sifra' => '226',
                'naziv' => 'Pančevo',
            ],
            [
                'sifra' => '077',
                'naziv' => 'Paraćin',
            ],
            [
                'sifra' => '247',
                'naziv' => 'Petrovaradin',
            ],
            [
                'sifra' => '078',
                'naziv' => 'Petrovac na Mlavi',
            ],
            [
                'sifra' => '314',
                'naziv' => 'Peć',
            ],
            [
                'sifra' => '227',
                'naziv' => 'Pećinci',
            ],
            [
                'sifra' => '079',
                'naziv' => 'Pirot',
            ],
            [
                'sifra' => '228',
                'naziv' => 'Plandište',
            ],
            [
                'sifra' => '315',
                'naziv' => 'Podujevo',
            ],
            [
                'sifra' => '080',
                'naziv' => 'Požarevac',
            ],
            [
                'sifra' => '081',
                'naziv' => 'Požega',
            ],
            [
                'sifra' => '082',
                'naziv' => 'Preševo',
            ],
            [
                'sifra' => '083',
                'naziv' => 'Priboj na Limu',
            ],
            [
                'sifra' => '317',
                'naziv' => 'Prizren',
            ],
            [
                'sifra' => '084',
                'naziv' => 'Prijepolje',
            ],
            [
                'sifra' => '316',
                'naziv' => 'Priština',
            ],
            [
                'sifra' => '085',
                'naziv' => 'Prokuplje',
            ],
            [
                'sifra' => '088',
                'naziv' => 'Ražanj',
            ],
            [
                'sifra' => '120',
                'naziv' => 'Rakovica',
            ],
            [
                'sifra' => '086',
                'naziv' => 'Rača',
            ],
            [
                'sifra' => '087',
                'naziv' => 'Raška',
            ],
            [
                'sifra' => '089',
                'naziv' => 'Rekovac',
            ],
            [
                'sifra' => '229',
                'naziv' => 'Ruma',
            ],
            [
                'sifra' => '016',
                'naziv' => 'Savski venac',
            ],
            [
                'sifra' => '097',
                'naziv' => 'Svilajnac',
            ],
            [
                'sifra' => '098',
                'naziv' => 'Svrljig',
            ],
            [
                'sifra' => '231',
                'naziv' => 'Senta',
            ],
            [
                'sifra' => '230',
                'naziv' => 'Sečanj',
            ],
            [
                'sifra' => '091',
                'naziv' => 'Sjenica',
            ],
            [
                'sifra' => '092',
                'naziv' => 'Smederevo',
            ],
            [
                'sifra' => '093',
                'naziv' => 'Smederevska Palanka',
            ],
            [
                'sifra' => '094',
                'naziv' => 'Sokobanja',
            ],
            [
                'sifra' => '232',
                'naziv' => 'Sombor',
            ],
            [
                'sifra' => '017',
                'naziv' => 'Sopot',
            ],
            [
                'sifra' => '318',
                'naziv' => 'Srbica',
            ],
            [
                'sifra' => '233',
                'naziv' => 'Srbobran',
            ],
            [
                'sifra' => '234',
                'naziv' => 'Sremska Mitrovica',
            ],
            [
                'sifra' => '250',
                'naziv' => 'Sremski Karlovci',
            ],
            [
                'sifra' => '235',
                'naziv' => 'Stara Pazova',
            ],
            [
                'sifra' => '018',
                'naziv' => 'Stari grad',
            ],
            [
                'sifra' => '123',
                'naziv' => 'Stragari',
            ],
            [
                'sifra' => '236',
                'naziv' => 'Subotica',
            ],
            [
                'sifra' => '319',
                'naziv' => 'Suva Reka',
            ],
            [
                'sifra' => '095',
                'naziv' => 'Surdulica',
            ],
            [
                'sifra' => '124',
                'naziv' => 'Surčin',
            ],
            [
                'sifra' => '238',
                'naziv' => 'Temerin',
            ],
            [
                'sifra' => '239',
                'naziv' => 'Titel',
            ],
            [
                'sifra' => '101',
                'naziv' => 'Topola',
            ],
            [
                'sifra' => '102',
                'naziv' => 'Trgovište',
            ],
            [
                'sifra' => '103',
                'naziv' => 'Trstenik',
            ],
            [
                'sifra' => '104',
                'naziv' => 'Tutin',
            ],
            [
                'sifra' => '032',
                'naziv' => 'Ćićevac',
            ],
            [
                'sifra' => '033',
                'naziv' => 'Ćuprija',
            ],
            [
                'sifra' => '105',
                'naziv' => 'Ub',
            ],
            [
                'sifra' => '100',
                'naziv' => 'Užice',
            ],
            [
                'sifra' => '320',
                'naziv' => 'Uroševac',
            ],
            [
                'sifra' => '126',
                'naziv' => 'Crveni krst',
            ],
            [
                'sifra' => '031',
                'naziv' => 'Crna Trava',
            ],
            [
                'sifra' => '035',
                'naziv' => 'Čajetina',
            ],
            [
                'sifra' => '034',
                'naziv' => 'Čačak',
            ],
            [
                'sifra' => '211',
                'naziv' => 'Čoka',
            ],
            [
                'sifra' => '011',
                'naziv' => 'Čukarica',
            ],
            [
                'sifra' => '099',
                'naziv' => 'Šabac',
            ],
            [
                'sifra' => '237',
                'naziv' => 'Šid',
            ],
            [
                'sifra' => '325',
                'naziv' => 'Štimlje',
            ],
            [
                'sifra' => '326',
                'naziv' => 'Štrpce',
            ],
        ];

        Opstina::insert($data);
    }
}
