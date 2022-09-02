<!DOCTYPE html>
<html>

<head>
    <title>City Government of Malolos - Business Itinerary</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
        integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
</head>

<body>
    <div>
        <table style="width: 100%">
            <tr>
                <td style="width:20%"><img src="{{ public_path('images/logo.png') }}" alt="profile Pic" height="75"
                        width="80"></td>
                <td style="width:60%;text-align: center;">

                    Republic of the Philippines</br>
                    Province of Bulacan</br>
                    City of Malolos</br>
                </td>
                <td style="width:10%"></td>
                <td style="">
                    <img src="{{ public_path('storage/qr/' . $qr) }}" alt="QRCODE" height="75" width="80">
                </td>
            </tr>
        </table>
        <div class="row">
            <div class="col-sm">


            </div>
            <div class="col-sm">


            </div>
            <div class="col-sm">

            </div>

        </div>
        <table class="" style="font-size:10px; vertical-align: middle;">
            <tr style="font-size:10px">
                <td>
                </td>

                <td colspan="2">

                </td>
                <td style="text-align: center;">

                </td>

                <td colspan="2">

                </td>
                <td></td>

            </tr>
            <tr>
                <td colspan="2">
                    Control Number: {{ $data[0]['control_number'] }}
                <td>
            </tr>
            <tr>
                <td colspan="2">

                    {{ $title }}
                <td>
            </tr>
            <tr>
                <td colspan="2">
                    Date: {{ $date }}
                <td>
            </tr>
            <tr>
                <td colspan="2">
                    <p>
                    <p>Here are of the list of businesses</p>
                    </p>
                <td>
            </tr>
        </table>
    </div>
    {{-- {{ $pivot }} --}}
    <div>
        <table class="table table-bordered" style="font-size:10px">
            <tr style="text-align: center;">
                <th>No.</th>

                <th>B.I.N.</th>
                <th>Business Name</th>
                <th>Owner</th>
                <th>Contact #</th>
                <th>Line of Business</th>
                <th>Address</th>
            </tr>
            <?php //foreach ($data as $key => $value){
            ?>
            <?php $count=0; foreach ($pivot as $keyin => $valuein){ $count=$count+1; ?>
            <tr>
                <td><?php echo $count; ?></td>
                <td><?php echo $valuein['bin']; ?></td>
                <td><?php echo $valuein['business_name']; ?></td>
                <td><?php echo $valuein['owner']; ?></td>
                <td><?php echo $valuein['contact_number']; ?></td>
                <td><?php echo $valuein['line_of_business']; ?></td>
                <td><?php echo $valuein['address']; ?></td>
            </tr>
            <?php foreach ($valuein['checklistdata'] as $k => $v){  ?>
            <tr>

                <td>
                    <input type="checkbox" name="inlineRadioOptionsWeek" id="Checkbox0" value="0">
                </td>
                <td colspan="7">
                    <?php echo $v['label']; ?>
                </td>

            </tr>

            <?php } ?>
            <tr rowspan="4">
                <td colspan="8">
                    <label for="">Remarks:</label>

                </td>
            </tr>
            <?php } ?>
            <?php //}
            ?>

        </table>
    </div>
</body>

</html>
