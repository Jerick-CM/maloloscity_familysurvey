<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MunicipalitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ini_set('memory_limit', '-1');
        DB::statement("INSERT INTO `municipalities` (`id`, `mun_id`, `prov_id`, `mun_name`, `pm`, `dist_id`, `dist_name`) VALUES
        (1, '01', 14, 'ANGAT', 1401, 3, '3RD'),
        (2, '02', 14, 'BALAGTAS', 1402, 2, '2ND'),
        (3, '03', 14, 'BALIUAG', 1403, 2, '2ND'),
        (4, '04', 14, 'BOCAUE', 1404, 2, '2ND'),
        (5, '05', 14, 'BULAKAN', 1405, 1, '1ST'),
        (6, '06', 14, 'BUSTOS', 1406, 2, '2ND'),
        (7, '07', 14, 'CALUMPIT', 1407, 1, '1ST'),
        (8, '08', 14, 'GUIGUINTO', 1408, 2, '2ND'),
        (9, '09', 14, 'HAGONOY', 1409, 1, '1ST'),
        (10, '10', 14, 'MALOLOS CITY', 1410, 1, '1ST'),
        (11, '11', 14, 'MARILAO', 1411, 4, '4TH'),
        (12, '12', 14, 'MEYCAUAYAN', 1412, 4, '4TH'),
        (13, '13', 14, 'NORZAGARAY', 1413, 3, '3RD'),
        (14, '14', 14, 'OBANDO', 1414, 4, '4TH'),
        (15, '15', 14, 'PANDI', 1415, 2, '2ND'),
        (16, '16', 14, 'PAOMBONG', 1416, 1, '1ST'),
        (17, '17', 14, 'PLARIDEL', 1417, 2, '2ND'),
        (18, '18', 14, 'PULILAN', 1418, 1, '1ST'),
        (19, '19', 14, 'SAN ILDEFONSO', 1419, 3, '3RD'),
        (20, '20', 14, 'SAN JOSE DEL MONTE CITY', 1420, 4, 'LONE'),
        (21, '21', 14, 'SAN MIGUEL', 1421, 3, '3RD'),
        (22, '22', 14, 'SAN RAFAEL', 1422, 3, '3RD'),
        (23, '23', 14, 'SANTA MARIA', 1423, 4, '4TH'),
        (24, '24', 14, 'DOÑA REMEDIOS TRINIDAD', 1424, 3, '3RD');");
    }
}
