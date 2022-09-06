<!DOCTYPE html>
<html>

<head>
    <title>City Government of Malolos - FAMILY SURVEY ON RISKS AND VULNERABILITY </title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
        integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
</head>

<body>
    <div>
        <table style="width: 100%">
            <tr>
                <td style="width:25%">
                    <img src="{{ public_path('images/logo.png') }}" alt="profile Pic" height="75" width="80">
                </td>

                <td style="width:50%;text-align: center;font-size:14px;">
                    Department od Social Welfare and Development</br>
                    FAMILY SURVEY ON RISK AND VULNERABILITY</br>

                </td>
                <td style="width:15%">
                </td>
                <td style="width:10%">

                    <img src="{{ public_path('images/dswd_primary.png') }}" alt="profile Pic" height="75"
                        width="80">
                </td>
            </tr>
        </table>
        <div class="row">
            <div class="col-sm">


            </div>
            <div class="col-sm">
                <br>
                <span style="font-size:14px;">Country: Republic of the Philippines</span>
                <br>
                <span style="font-size:14px;">Province: Province of Bulacan</span>
                <br>
                <span style="font-size:14px;">City/Municipality : City of Malolos</span>
                <br>
                <span style="font-size:14px;">Barangay: {{ $barangay }}</span>
                <br>
            </div>
            <div class="col-sm">

            </div>

        </div>
        <br>
        <table id="survey" class="w-full border-collapse border border-slate-500 table-fixed md:table-fixed">
            <thead>
                <tr>
                    <td width="70%">
                        II. MGA PANGANIB (RISK) AT VULNERABILITY NA
                        KINAHARAP NG PAMILYA
                    </td>
                    <td>Lagyan ng (x) kung wala</td>
                    <td>Ngayong taon</td>
                    <td>2-5 taon na nakalipas</td>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>INDIVIDUAL LIFE CYCLE RISKS</td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>

                <tr>
                    <td>1. Pagbubuntis at panganganak</td>
                    <td class="text-center">
                        {{ $ilr[0]->i1a }}
                    </td>
                    <td class="text-center">
                        {{ $ilr[0]->i1b }}
                    </td>
                    <td class="text-center">
                        {{ $ilr[0]->i1c }}
                    </td>
                </tr>
                <tr>
                    <td>2. Hindi nakapagpre-natal check up</td>
                    <td class="text-center">
                        {{ $ilr[0]->i2a }}
                    </td>
                    <td class="text-center">
                        {{ $ilr[0]->i2b }}
                    </td>
                    <td class="text-center">
                        {{ $ilr[0]->i2c }}
                    </td>
                </tr>
                <tr>
                    <td>3. Pagkalagas (abortion)</td>
                    <td class="text-center">
                        {{ $ilr[0]->i3a }}
                    </td>
                    <td class="text-center">
                        {{ $ilr[0]->i3b }}
                    </td>
                    <td class="text-center">
                        {{ $ilr[0]->i3c }}
                    </td>
                </tr>
                <tr>
                    <td>4. Nakunan</td>
                    <td class="text-center">
                        {{ $ilr[0]->i4a }}
                    </td>
                    <td class="text-center">
                        {{ $ilr[0]->i4b }}
                    </td>
                    <td class="text-center">
                        {{ $ilr[0]->i4c }}
                    </td>
                </tr>
                <tr>
                    <td>
                        5. Pagkawala ng anak na may kapansanan ng
                        isinilang
                    </td>
                    <td class="text-center">
                        {{ $ilr[0]->i5a }}
                    </td>
                    <td class="text-center">
                        {{ $ilr[0]->i5b }}
                    </td>
                    <td class="text-center">
                        {{ $ilr[0]->i5c }}
                    </td>
                </tr>
                <tr>
                    <td>6. Hindi nakapagpost-natal check up</td>
                    <td class="text-center">
                        {{ $ilr[0]->i6a }}
                    </td>
                    <td class="text-center">
                        {{ $ilr[0]->i6b }}
                    </td>
                    <td class="text-center">
                        {{ $ilr[0]->i6c }}
                    </td>
                </tr>

                <tr>
                    <td>
                        7. Pagkakaroon ng anak na may kapansanan ng
                        isinilang
                    </td>
                    <td class="text-center">
                        {{ $ilr[0]->i7a }}
                    </td>
                    <td class="text-center">
                        {{ $ilr[0]->i7b }}
                    </td>
                    <td class="text-center">
                        {{ $ilr[0]->i7c }}
                    </td>
                </tr>

                <tr>
                    <td>8. Di nabakunahan ang mga anak o bata</td>
                    <td class="text-center">
                        {{ $ilr[0]->i8a }}
                    </td>
                    <td class="text-center">
                        {{ $ilr[0]->i8b }}
                    </td>
                    <td class="text-center">
                        {{ $ilr[0]->i8c }}
                    </td>
                </tr>

                <tr>
                    <td>
                        9. Miyembro ng pamilya na may malubhang
                        kapansanan dahil sa sakit o aksidente
                    </td>
                    <td class="text-center">
                        {{ $ilr[0]->i9a }}
                    </td>
                    <td class="text-center">
                        {{ $ilr[0]->i9b }}
                    </td>
                    <td class="text-center">
                        {{ $ilr[0]->i9c }}
                    </td>
                </tr>
                <tr>
                    <td>
                        10. Namatay dahils sa sakuna o aksidente
                    </td>
                    <td class="text-center">
                        {{ $ilr[0]->i10a }}
                    </td>
                    <td class="text-center">
                        {{ $ilr[0]->i10b }}
                    </td>
                    <td class="text-center">
                        {{ $ilr[0]->i10c }}
                    </td>
                </tr>

                <tr>
                    <td>
                        11. Miyembro ng pamilya na may malubha o
                        matagal nang sakit?
                    </td>
                    <td class="text-center">
                        {{ $ilr[0]->i11a }}
                    </td>
                    <td class="text-center">
                        {{ $ilr[0]->i11b }}
                    </td>
                    <td class="text-center">
                        {{ $ilr[0]->i11c }}
                    </td>
                </tr>
                <tr>
                    <td>
                        12. Di nakatanggap ng serbisyong medikal sa
                        health center o ospital?
                    </td>
                    <td class="text-center">
                        {{ $ilr[0]->i12a }}
                    </td>
                    <td class="text-center">
                        {{ $ilr[0]->i12b }}
                    </td>
                    <td class="text-center">
                        {{ $ilr[0]->i12c }}
                    </td>
                </tr>
                <tr>
                    <td>
                        13. Di nakabili ng kinakailangang gamot"
                    </td>
                    <td class="text-center">
                        {{ $ilr[0]->i13a }}
                    </td>
                    <td class="text-center">
                        {{ $ilr[0]->i13b }}
                    </td>
                    <td class="text-center">
                        {{ $ilr[0]->i13c }}
                    </td>
                </tr>
                <tr>
                    <td>
                        14. Miyembro ng pamilya na pumanaw dahil sa
                        karamdaman
                    </td>
                    <td class="text-center">
                        {{ $ilr[0]->i14a }}
                    </td>
                    <td class="text-center">
                        {{ $ilr[0]->i14b }}
                    </td>
                    <td class="text-center">
                        {{ $ilr[0]->i14c }}
                    </td>
                </tr>
                <tr>
                    <td>
                        15. Di nai-enrol sa day care o pre-school
                        ang mga anak
                    </td>
                    <td class="text-center">
                        {{ $ilr[0]->i15a }}
                    </td>
                    <td class="text-center">
                        {{ $ilr[0]->i15b }}
                    </td>
                    <td class="text-center">
                        {{ $ilr[0]->i15c }}
                    </td>
                </tr>
                <tr>
                    <td>16. Di nai-enrol sa elemtarya ang anak</td>
                    <td class="text-center">
                        {{ $ilr[0]->i16a }}
                    </td>
                    <td class="text-center">
                        {{ $ilr[0]->i16b }}
                    </td>
                    <td class="text-center">
                        {{ $ilr[0]->i16c }}
                    </td>
                </tr>

                <tr>
                    <td>
                        17. Di nai-enrol sa high school ang anak
                    </td>
                    <td class="text-center">
                        {{ $ilr[0]->i17a }}
                    </td>
                    <td class="text-center">
                        {{ $ilr[0]->i17b }}
                    </td>
                    <td class="text-center">
                        {{ $ilr[0]->i17c }}
                    </td>
                </tr>

                <tr>
                    <td>
                        18. Di nai-enrol o nahinto sa kolehiyo ang
                        anak
                    </td>
                    <td class="text-center">
                        {{ $ilr[0]->i18a }}
                    </td>
                    <td class="text-center">
                        {{ $ilr[0]->i18b }}
                    </td>
                    <td class="text-center">
                        {{ $ilr[0]->i18c }}
                    </td>
                </tr>

                <tr>
                    <td>
                        19. Tumigil sa pag-aaral o nagdrop out ng
                        elementarya o high school ang anak
                    </td>
                    <td class="text-center">
                        {{ $ilr[0]->i19a }}
                    </td>
                    <td class="text-center">
                        {{ $ilr[0]->i19b }}
                    </td>
                    <td class="text-center">
                        {{ $ilr[0]->i19c }}
                    </td>
                </tr>

                <tr>
                    <td>20. Nalulong sa bawal na gamot o alak</td>
                    <td class="text-center">
                        {{ $ilr[0]->i20a }}
                    </td>
                    <td class="text-center">
                        {{ $ilr[0]->i20b }}
                    </td>
                    <td class="text-center">
                        {{ $ilr[0]->i20c }}
                    </td>
                </tr>
                <tr>
                    <td>
                        21. Nabuntis na menor de edad? (below 18
                        years old)
                    </td>
                    <td class="text-center">
                        {{ $ilr[0]->i21a }}
                    </td>
                    <td class="text-center">
                        {{ $ilr[0]->i21b }}
                    </td>
                    <td class="text-center">
                        {{ $ilr[0]->i21c }}
                    </td>
                </tr>

                <tr>
                    <td>
                        22. Naglayas o hindi umuwi ng bahay ng
                        walang paalam sa magulang
                    </td>
                    <td class="text-center">
                        {{ $ilr[0]->i22a }}
                    </td>
                    <td class="text-center">
                        {{ $ilr[0]->i22b }}
                    </td>
                    <td class="text-center">
                        {{ $ilr[0]->i22c }}
                    </td>
                </tr>
                <tr>
                    <td>23. Miyembro na nangibang bansa</td>
                    <td class="text-center">
                        {{ $ilr[0]->i23a }}
                    </td>
                    <td class="text-center">
                        {{ $ilr[0]->i23b }}
                    </td>
                    <td class="text-center">
                        {{ $ilr[0]->i23c }}
                    </td>
                </tr>
                <tr>
                    <td>
                        24. Naghahanapbuhay sa ibang munisipyo o
                        siyudad
                    </td>
                    <td class="text-center">
                        {{ $ilr[0]->i24a }}
                    </td>
                    <td class="text-center">
                        {{ $ilr[0]->i24b }}
                    </td>
                    <td class="text-center">
                        {{ $ilr[0]->i24c }}
                    </td>
                </tr>
                <tr>
                    <td>25. Hiwalay sa asawa</td>
                    <td class="text-center">
                        {{ $ilr[0]->i25a }}
                    </td>
                    <td class="text-center">
                        {{ $ilr[0]->i25b }}
                    </td>
                    <td class="text-center">
                        {{ $ilr[0]->i25c }}
                    </td>
                </tr>
                <tr>
                    <td>26. Namatayan ng asawa</td>
                    <td class="text-center">
                        {{ $ilr[0]->i26a }}
                    </td>
                    <td class="text-center">
                        {{ $ilr[0]->i26b }}
                    </td>
                    <td class="text-center">
                        {{ $ilr[0]->i26c }}
                    </td>
                </tr>
                <tr>
                    <td>
                        27. Di sapat ang pagkain ng pamilya o
                        naranasan ang kawalan ng pagkain
                    </td>
                    <td class="text-center">
                        {{ $ilr[0]->i27a }}
                    </td>
                    <td class="text-center">
                        {{ $ilr[0]->i27b }}
                    </td>
                    <td class="text-center">
                        {{ $ilr[0]->i27c }}
                    </td>
                </tr>

                <tr>
                    <td>
                        28. kakulangan ng malinis na inuming tubig
                    </td>
                    <td class="text-center">
                        {{ $ilr[0]->i28a }}
                    </td>
                    <td class="text-center">
                        {{ $ilr[0]->i28b }}
                    </td>
                    <td class="text-center">
                        {{ $ilr[0]->i28c }}
                    </td>
                </tr>

                <tr>
                    <td>
                        29. Bahay ay gawa sa light /salvaged
                        material
                    </td>
                    <td class="text-center">
                        {{ $ilr[0]->i29a }}
                    </td>
                    <td class="text-center">
                        {{ $ilr[0]->i29b }}
                    </td>
                    <td class="text-center">
                        {{ $ilr[0]->i29c }}
                    </td>
                </tr>

                <tr>
                    <td>
                        30. Kawalan ng malinis na CR o palikuran
                    </td>
                    <td class="text-center">
                        {{ $ilr[0]->i30a }}
                    </td>
                    <td class="text-center">
                        {{ $ilr[0]->i30b }}
                    </td>
                    <td class="text-center">
                        {{ $ilr[0]->i30c }}
                    </td>
                </tr>

                <!-- table 2 - 30  -->
                <tr>
                    <td>ECONOMIC RISK</td>
                    <td class="text-center">

                    </td>
                    <td class="text-center">

                    </td>
                    <td class="text-center">

                    </td>
                </tr>
                <tr>
                    <td>31. Walang hanapbuhay</td>
                    <td class="text-center">
                        {{ $er[0]->e31a }}
                    </td>
                    <td class="text-center">
                        {{ $er[0]->e31b }}
                    </td>
                    <td class="text-center">
                        {{ $er[0]->e31c }}
                    </td>
                </tr>
                <tr>
                    <td>
                        32. Hanapbuhay na di akma sa tinapos na
                        kurso
                    </td>
                    <td class="text-center">
                        {{ $er[0]->e32a }}
                    </td>
                    <td class="text-center">
                        {{ $er[0]->e32b }}
                    </td>
                    <td class="text-center">
                        {{ $er[0]->e32c }}
                    </td>
                </tr>
                <tr>
                    <td>
                        33. Naranasang maaksidente habang nasa
                        trabaho
                    </td>
                    <td class="text-center">
                        {{ $er[0]->e33a }}
                    </td>
                    <td class="text-center">
                        {{ $er[0]->e33b }}
                    </td>
                    <td class="text-center">
                        {{ $er[0]->e33c }}
                    </td>
                </tr>
                <tr>
                    <td>34. Di nakabayad ng utang</td>
                    <td class="text-center">
                        {{ $er[0]->e34a }}
                    </td>
                    <td class="text-center">
                        {{ $er[0]->e34b }}
                    </td>
                    <td class="text-center">
                        {{ $er[0]->e34c }}
                    </td>
                </tr>
                <tr>
                    <td>35. Biktima ng scam</td>
                    <td class="text-center">
                        {{ $er[0]->e35a }}
                    </td>
                    <td class="text-center">
                        {{ $er[0]->e35b }}
                    </td>
                    <td class="text-center">
                        {{ $er[0]->e35c }}
                    </td>
                </tr>
                <tr>
                    <td>36. Biktima ng illegal recruiter</td>
                    <td class="text-center">
                        {{ $er[0]->e36a }}
                    </td>
                    <td class="text-center">
                        {{ $er[0]->e36b }}
                    </td>
                    <td class="text-center">
                        {{ $er[0]->e36c }}
                    </td>
                </tr>
                <tr>
                    <td>
                        37. Lumipat ng tirahan sa ibang munisipyo
                    </td>
                    <td class="text-center">
                        {{ $er[0]->e37a }}
                    </td>
                    <td class="text-center">
                        {{ $er[0]->e37b }}
                    </td>
                    <td class="text-center">
                        {{ $er[0]->e37c }}
                    </td>
                </tr>
                <tr>
                    <td>38. Walang life insurance</td>
                    <td class="text-center">
                        {{ $er[0]->e38a }}
                    </td>
                    <td class="text-center">
                        {{ $er[0]->e38b }}
                    </td>
                    <td class="text-center">
                        {{ $er[0]->e38c }}
                    </td>
                </tr>
                <tr>
                    <td>39. Walang health insurance</td>
                    <td class="text-center">
                        {{ $er[0]->e39a }}
                    </td>
                    <td class="text-center">
                        {{ $er[0]->e39b }}
                    </td>
                    <td class="text-center">
                        {{ $er[0]->e39c }}
                    </td>
                </tr>
                <tr>
                    <td>
                        40. Naghahanapbuhay pero walang SSS o GSIS
                    </td>
                    <td class="text-center">
                        {{ $er[0]->e40a }}
                    </td>
                    <td class="text-center">
                        {{ $er[0]->e40b }}
                    </td>
                    <td class="text-center">
                        {{ $er[0]->e40c }}
                    </td>
                </tr>
                <tr>
                    <td>41. Pagkalugi sa negosyo</td>
                    <td class="text-center">
                        {{ $er[0]->e41a }}
                    </td>
                    <td class="text-center">
                        {{ $er[0]->e41b }}
                    </td>
                    <td class="text-center">
                        {{ $er[0]->e41c }}
                    </td>
                </tr>
                <tr>
                    <td>42. Pagsangla ng ari-arian</td>
                    <td class="text-center">
                        {{ $er[0]->e42a }}
                    </td>
                    <td class="text-center">
                        {{ $er[0]->e42b }}
                    </td>
                    <td class="text-center">
                        {{ $er[0]->e42c }}
                    </td>
                </tr>
                <tr>
                    <td>43. Paghiram ng pera sa kamag-anak</td>
                    <td class="text-center">
                        {{ $er[0]->e43a }}
                    </td>
                    <td class="text-center">
                        {{ $er[0]->e43b }}
                    </td>
                    <td class="text-center">
                        {{ $er[0]->e43c }}
                    </td>
                </tr>
                <tr>
                    <td>
                        44. Kapamilya na nakakatanda na di
                        nakkatanggap ng pension
                    </td>
                    <td class="text-center">
                        {{ $er[0]->e44a }}
                    </td>
                    <td class="text-center">
                        {{ $er[0]->e44b }}
                    </td>
                    <td class="text-center">
                        {{ $er[0]->e44c }}
                    </td>
                </tr>
                <!-- economic risk 14 rows -->
                <tr>
                    <td>ENVIRONMENT AND DISASTER RISKS</td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td>45. Biktima ng sunog</td>
                    <td class="text-center">
                        {{ $edr[0]->edr45a }}
                    </td>
                    <td class="text-center">
                        {{ $edr[0]->edr45b }}
                    </td>
                    <td class="text-center">
                        {{ $edr[0]->edr45c }}
                    </td>
                </tr>
                <tr>
                    <td>46. Biktima ng bagyo o tagtuyot</td>
                    <td class="text-center">
                        {{ $edr[0]->edr46a }}
                    </td>
                    <td class="text-center">
                        {{ $edr[0]->edr46b }}
                    </td>
                    <td class="text-center">
                        {{ $edr[0]->edr46c }}
                    </td>
                </tr>
                <tr>
                    <td>
                        47. Biktima ng lindol o pagputok ng bulkan
                    </td>
                    <td class="text-center">
                        {{ $edr[0]->edr47a }}
                    </td>
                    <td class="text-center">
                        {{ $edr[0]->edr47b }}
                    </td>
                    <td class="text-center">
                        {{ $edr[0]->edr47c }}
                    </td>
                </tr>
                <tr>
                    <td>
                        48. Nawalan ng miyembro ng pamilya sanhi ng
                        kalamidad
                    </td>
                    <td class="text-center">
                        {{ $edr[0]->edr48a }}
                    </td>
                    <td class="text-center">
                        {{ $edr[0]->edr48b }}
                    </td>
                    <td class="text-center">
                        {{ $edr[0]->edr48c }}
                    </td>
                </tr>
                <tr>
                    <td>49. Demolisyon</td>
                    <td class="text-center">
                        {{ $edr[0]->edr49a }}
                    </td>
                    <td class="text-center">
                        {{ $edr[0]->edr49b }}
                    </td>
                    <td class="text-center">
                        {{ $edr[0]->edr49c }}
                    </td>
                </tr>
                <tr>
                    <td>50. Tumira sa relocation o resettlement</td>
                    <td class="text-center">
                        {{ $edr[0]->edr50a }}
                    </td>
                    <td class="text-center">
                        {{ $edr[0]->edr50b }}
                    </td>
                    <td class="text-center">
                        {{ $edr[0]->edr50c }}
                    </td>
                </tr>
                <!-- environmental risk 6 rows -->
                <!-- social and governance risk 24 rows -->
                <tr>
                    <td>SOCIAL AND GOVERNANCE RISKS</td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td>51. Biktima ng pagnanakaw sa bahay</td>
                    <td class="text-center">
                        {{ $sgr[0]->s51a }}
                    </td>
                    <td class="text-center">
                        {{ $sgr[0]->s51b }}
                    </td>
                    <td class="text-center">
                        {{ $sgr[0]->s51c }}
                    </td>
                </tr>
                <tr>
                    <td>
                        52. Nakaranas ng pagnanakaw o hold-up sa
                        daan
                    </td>
                    <td class="text-center">
                        {{ $sgr[0]->s52a }}
                    </td>
                    <td class="text-center">
                        {{ $sgr[0]->s52b }}
                    </td>
                    <td class="text-center">
                        {{ $sgr[0]->s52c }}
                    </td>
                </tr>
                <tr>
                    <td>53. Nakaranas ng sekswal na karahasan</td>
                    <td class="text-center">
                        {{ $sgr[0]->s53a }}
                    </td>
                    <td class="text-center">
                        {{ $sgr[0]->s53b }}
                    </td>
                    <td class="text-center">
                        {{ $sgr[0]->s53c }}
                    </td>
                </tr>
                <tr>
                    <td>
                        54. May alam po ba kayong "gang" ng kabataan
                        sa barangay
                    </td>
                    <td class="text-center">
                        {{ $sgr[0]->s54a }}
                    </td>
                    <td class="text-center">
                        {{ $sgr[0]->s54b }}
                    </td>
                    <td class="text-center">
                        {{ $sgr[0]->s54c }}
                    </td>
                </tr>
                <tr>
                    <td>
                        55. Namatayan ng kapamilya dahil sa krimen
                    </td>
                    <td class="text-center">
                        {{ $sgr[0]->s55a }}
                    </td>
                    <td class="text-center">
                        {{ $sgr[0]->s55b }}
                    </td>
                    <td class="text-center">
                        {{ $sgr[0]->s55c }}
                    </td>
                </tr>
                <tr>
                    <td>
                        56 Nakakita ng aktuwal na krimen na naganap
                        sa komunidad
                    </td>
                    <td class="text-center">
                        {{ $sgr[0]->s56a }}
                    </td>
                    <td class="text-center">
                        {{ $sgr[0]->s56b }}
                    </td>
                    <td class="text-center">
                        {{ $sgr[0]->s56c }}
                    </td>
                </tr>
                <tr>
                    <td>57. Karahasan kaugnay sa eleksyon</td>
                    <td class="text-center">
                        {{ $sgr[0]->s57a }}
                    </td>
                    <td class="text-center">
                        {{ $sgr[0]->s57b }}
                    </td>
                    <td class="text-center">
                        {{ $sgr[0]->s57c }}
                    </td>
                </tr>
                <tr>
                    <td>
                        58. Takot at panggugulo dahil sa pulitika
                    </td>
                    <td class="text-center">
                        {{ $sgr[0]->s58a }}
                    </td>
                    <td class="text-center">
                        {{ $sgr[0]->s58b }}
                    </td>
                    <td class="text-center">
                        {{ $sgr[0]->s58c }}
                    </td>
                </tr>
                <tr>
                    <td>59. Pag-aalsa laban sa pamahalaan</td>
                    <td class="text-center">
                        {{ $sgr[0]->s59a }}
                    </td>
                    <td class="text-center">
                        {{ $sgr[0]->s59b }}
                    </td>
                    <td class="text-center">
                        {{ $sgr[0]->s59c }}
                    </td>
                </tr>

                <tr>
                    <td>60. Pangugulo ng militar</td>
                    <td class="text-center">
                        {{ $sgr[0]->s60a }}
                    </td>
                    <td class="text-center">
                        {{ $sgr[0]->s60b }}
                    </td>
                    <td class="text-center">
                        {{ $sgr[0]->s60c }}
                    </td>
                </tr>
                <tr>
                    <td>
                        61. Karahasan sa tahanan (pananakit ng
                        asawa, anak etc.
                    </td>
                    <td class="text-center">
                        {{ $sgr[0]->s61a }}
                    </td>
                    <td class="text-center">
                        {{ $sgr[0]->s61b }}
                    </td>
                    <td class="text-center">
                        {{ $sgr[0]->s61c }}
                    </td>
                </tr>
                <tr>
                    <td>62. Pang-aabuso sa menor de edad</td>
                    <td class="text-center">
                        {{ $sgr[0]->s62a }}
                    </td>
                    <td class="text-center">
                        {{ $sgr[0]->s62b }}
                    </td>
                    <td class="text-center">
                        {{ $sgr[0]->s62c }}
                    </td>
                </tr>
                <tr>
                    <td>63. Pagtatrabaho ng menor de edad</td>
                    <td class="text-center">
                        {{ $sgr[0]->s63a }}
                    </td>
                    <td class="text-center">
                        {{ $sgr[0]->s63b }}
                    </td>
                    <td class="text-center">
                        {{ $sgr[0]->s63c }}
                    </td>
                </tr>
                <tr>
                    <td>
                        64. Katiwalian sa pamahalaan (corruption,
                        bribery, embezzlement etc)
                    </td>
                    <td class="text-center">
                        {{ $sgr[0]->s64a }}
                    </td>
                    <td class="text-center">
                        {{ $sgr[0]->s64b }}
                    </td>
                    <td class="text-center">
                        {{ $sgr[0]->s64c }}
                    </td>
                </tr>
                <tr>
                    <td>
                        65. Di nakikilahok sa aktibidades ng
                        komunidad
                    </td>
                    <td class="text-center">
                        {{ $sgr[0]->s65a }}
                    </td>
                    <td class="text-center">
                        {{ $sgr[0]->s65b }}
                    </td>
                    <td class="text-center">
                        {{ $sgr[0]->s65c }}
                    </td>
                </tr>
                <tr>
                    <td>66. Di bumoboto</td>
                    <td class="text-center">
                        {{ $sgr[0]->s66a }}
                    </td>
                    <td class="text-center">
                        {{ $sgr[0]->s66b }}
                    </td>
                    <td class="text-center">
                        {{ $sgr[0]->s66c }}
                    </td>
                </tr>
                <tr>
                    <td>67. Di kasapi ng anumang organisasyon</td>
                    <td class="text-center">
                        {{ $sgr[0]->s67a }}
                    </td>
                    <td class="text-center">
                        {{ $sgr[0]->s67b }}
                    </td>
                    <td class="text-center">
                        {{ $sgr[0]->s67c }}
                    </td>
                </tr>
                <tr>
                    <td>68. Pag-angkin ng lupa ng iba</td>
                    <td class="text-center">
                        {{ $sgr[0]->s68a }}
                    </td>
                    <td class="text-center">
                        {{ $sgr[0]->s68b }}
                    </td>
                    <td class="text-center">
                        {{ $sgr[0]->s68c }}
                    </td>
                </tr>
                <tr>
                    <td>69. Tradisyon na nakakasama sa tao.</td>
                    <td class="text-center">
                        {{ $sgr[0]->s69a }}
                    </td>
                    <td class="text-center">
                        {{ $sgr[0]->s69b }}
                    </td>
                    <td class="text-center">
                        {{ $sgr[0]->s69c }}
                    </td>
                </tr>

                <tr>
                    <td>
                        70. Kaguluhan o di pagkakaunawaan dahil sa
                        relihiyon
                    </td>
                    <td class="text-center">
                        {{ $sgr[0]->s70a }}
                    </td>
                    <td class="text-center">
                        {{ $sgr[0]->s70b }}
                    </td>
                    <td class="text-center">
                        {{ $sgr[0]->s70c }}
                    </td>
                </tr>
                <tr>
                    <td>
                        71. Nakaranas ng mabagal na judicial system
                    </td>
                    <td class="text-center">
                        {{ $sgr[0]->s71a }}
                    </td>
                    <td class="text-center">
                        {{ $sgr[0]->s71b }}
                    </td>
                    <td class="text-center">
                        {{ $sgr[0]->s71c }}
                    </td>
                </tr>
                <tr>
                    <td>
                        72. Diskriminasyon dahil sa pinagmulan o
                        kasarian (ethnicity, gender, etc)
                    </td>
                    <td class="text-center">
                        {{ $sgr[0]->s72a }}
                    </td>
                    <td class="text-center">
                        {{ $sgr[0]->s72b }}
                    </td>
                    <td class="text-center">
                        {{ $sgr[0]->s72c }}
                    </td>
                </tr>
                <tr>
                    <td>
                        73. Alitan o tunggali dahil sa pagsasalungat
                        ng tribu
                    </td>
                    <td class="text-center">
                        {{ $sgr[0]->s73a }}
                    </td>
                    <td class="text-center">
                        {{ $sgr[0]->s73b }}
                    </td>
                    <td class="text-center">
                        {{ $sgr[0]->s73c }}
                    </td>
                </tr>
                <tr>
                    <td>
                        74. Ibang panganib na kinakaharap ng
                        mag-anak
                    </td>
                    <td class="text-center">
                        {{ $sgr[0]->s74a }}
                    </td>
                    <td class="text-center">
                        {{ $sgr[0]->s74b }}
                    </td>
                    <td class="text-center">
                        {{ $sgr[0]->s74c }}
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

</body>
<style>
    #survey {
        height: 2em;
        word-wrap: break-word;
        border: 1px solid black;
        font-size:12px;
    }

    #survey th {
        border: solid 1px black
    }

    #survey td {
        font-size:14px;
        border: solid 1px black
    }
</style>

</html>
