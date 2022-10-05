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
                    Department of Social Welfare and Development</br>
                    Informal Settler Families (ISF) and Illegal Encroachments</br>

                </td>
                <td style="width:15%">
                </td>
                <td style="width:10%">

                    <img src="{{ public_path('images/dswd_primary.png') }}" alt="profile Pic" height="75"
                        width="80">
                </td>
            </tr>
        </table>
        <br />
        <br />
        <div class="row">
            <table id="survey" class="w-full border-collapse border border-slate-500 table-fixed md:table-fixed">
                <thead>
                    <th>CONTROL NO.</th>
                    <th>WaterBody Name</th>
                    <th>WaterBody Type</th>
                    <th>Household Head(SURNAME,FIRSTNAME,MIDDLENAME)</th>
                    <th>BIRTHDATE</th>
                    <th>SPOUSE'S NAME (MAIDEN NAME IF FEMALE )</th>
                    <th>BIRTHDATE </th>
                    <th>TENURIAL STATUS </th>
                    <th>NO. OF FAMILY MEMBERS </th>
                    <th>STREET </th>
                    <th>BARANGAY </th>
                    <th>LATITUDE </th>
                    <th>LONGITUDE </th>

                    <th>BALIK PROBINSYA (BP) </th>
                    <th>EASEMENT ALONG THE WATERWAY(IN METERS)</th>
                    <th>LAND CLASSIFICATION </th>
                    <th>DATE GATHERED </th>
                </thead>
                <tbody>
                    {{-- 18 --}}
                    <?php
                    $count = 0; 
                    foreach ($data as $key => $value) { 
                        $count++;
                        ?>
                    <tr>
                        <td><?php echo $count; ?></td>
                        <td><?php echo $value['body_of_water_name']; ?></td>
                        <td><?php echo $value['body_of_water_type']; ?></td>
                        <td><?php echo $value['household_head']; ?></td>
                        <td><?php echo $value['birthdate']; ?></td>
                        <td><?php echo $value['spouse_name']; ?></td>
                        <td><?php echo $value['spouse_birthdate']; ?></td>
                        <td><?php echo $value['tenurial_status']; ?></td>
                        <td><?php echo $value['no_of_family_members']; ?></td>
                        <td><?php echo $value['street']; ?></td>
                        <td><?php echo $value['barangay']; ?></td>
                        <td><?php echo $value['latitude']; ?></td>
                        <td><?php echo $value['longitude']; ?></td>
                        <td><?php echo $value['balik_probinsya']; ?></td>
                        <td><?php echo $value['distance_to_waterway']; ?></td>
                        <td><?php echo $value['zone']; ?></td>
                        <td><?php echo $value['date']; ?></td>
                    </tr>

                    <?php } ?>

                </tbody>
            </table>
        </div>

    </div>

</body>
<style>
    #survey {
        height: 2em;
        word-wrap: break-word;
        border: 1px solid black;
        font-size: 8px;
    }

    #survey th {
        border: solid 1px black
    }

    #survey td {
        font-size: 8px;
        border: solid 1px black
    }
</style>

</html>
