<?php
// Connection to database
$dbc = mysqli_connect('localhost', 'root', '', 'krud');

//Add Record into the database
$queryMsg = '';
if (isset($_POST['saveButton'])) {
    $upn = $_POST['PayrollNum'];
    $fullName = $_POST['FullName'];
    $agenda = $_POST['Agenda'];
    $dateVisited = $_POST['DateVisited'];

    $insertSql = "INSERT INTO visitor (PayrollNum, FullName, Agenda, DateVisited)
                  VALUES ($upn, '$fullName', '$agenda', '$dateVisited')";
    if (mysqli_query($dbc, $insertSql)) {
        $queryMsg = "Record saved Successfully";
    } else {
        $queryMsg = "Could not save record!";
    }
}

if (!$dbc) {
    $krudMsg = "Could not connect to data source!";
} else {
    // Read and run query
    $readQuery = "SELECT * FROM visitor";
    $readResult = mysqli_query($dbc, $readQuery);
    if (!$readResult) {
        $krudMsg = "Query failure: " . mysqli_error($dbc);
    } else {
        //$oneRow = mysqli_fetch_row($readResult); 
        //echo 'Results using mysqli_fetch_row():<pre>',print_r($oneRow),'</pre>'; 
        //echo 'Results using mysqli_fetch_all():<pre>', print_r(mysqli_fetch_all($readResult, MYSQLI_ASSOC)), '</pre>';
        //echo 'Results using mysqli_fetch_row():<pre>', print_r(mysqli_fetch_row($readResult)), '</pre>';
        //echo 'Results using mysqli_fetch_array():<pre>', print_r(mysqli_fetch_array($readResult)), '</pre>';
        //echo 'Results using mysqli_fetch_assoc():<pre>', print_r(mysqli_fetch_assoc($readResult)), '</pre>';
        //echo 'Results using mysqli_fetch_fields():<pre>', print_r(mysqli_fetch_fields($readResult)), '</pre>';

        // Count the number of rows in database
        $krudMsg = number_format(mysqli_num_rows($readResult)); // . ' Records returned';
        $dbTableFields = mysqli_fetch_fields($readResult); // Fetch all fields in each column
        $tHeadRow = "\r\n<tr>\r\n<th>#</th>";               // Create table rows 
        //echo '1. tr beginning: '. $tHeadRow; 
        // Generate the table headers of each column
        foreach ($dbTableFields as $oneField) {
            $tHeadRow .= "\t<th>" . $oneField->name . "</th>";
        }
        $tHeadRow .= "\r\n</tr>\r\n";
        //echo '<br>2. tr ending: '. $tHeadRow;

        $tBodyRows = "";
        $rowCount = 0; // Initialize row counter.
        while ($oneRecord = mysqli_fetch_row($readResult)) {
            $tBodyRows .= "\r\n<tr class='' ondblclick='viewOneRecord(this)' onclick='highlightOneRow(this)'>\r\n";
            $rowCount += 1; // Increament the row serialization
            $tBodyRows .= "\t<td>$rowCount.</td>"; // Serialize table rows
            foreach ($oneRecord as $oneColumn) {
                $tBodyRows .= "\t<td>" . $oneColumn . "</td>";
            }
            $tBodyRows .= "\r\n</tr>\r\n";
        }
        //echo 'Results using mysqli_fetch_assoc():<pre>', print_r(mysqli_fetch_assoc($readResult)), '</pre>';
        $tFootRow = $tHeadRow;
    }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="/bsicons/bootstrap-icons.css">
    <link rel="stylesheet" href="/bootstrap/4/css/bootstrap.min.css">
    <title>CRUDing 2021</title>
    <style>
        * {
            font-size: 0.9rem;
        }

        .tSelectorBar {
            background: navy;
            color: white;
        }

        .table-sm td {
            padding: 0.1rem;
        }

        tbody tr:hover {
            background: khaki;
        }
    </style>
</head>

<body>
    <?php include('menubar.inc'); ?>
    <div class="container-fluid">
        <div class="row justify-content-center alert alert-warning" hidden>
            <?php echo $queryMsg; ?>
        </div>

        <div class="alert justify-content-center border">
            <div class="form-inline mb-1 alert shadow alert-secondary p-1">
                <input type="text" class="form-control form-control-sm mr-5" placeholder="Search box">
                <button type="button" class="btn btn-sm btn-outline-secondary mr-1 bi-eye" title="View Record" data-toggle="modal" data-target="#addModal" onClick="buttonActions(4);"></button>
                <button type="button" class="btn btn-sm btn-outline-secondary mr-1 bi-plus" title="Add Record" data-toggle="modal" data-target="#addModal" onClick="buttonActions(1);"></button>
                <button type="button" class="btn btn-sm btn-outline-secondary mr-1 bi-triangle" title="Change Record" data-toggle="modal" data-target="#addModal" onClick="buttonActions(2);"></button>
                <button type="button" class="btn btn-sm btn-outline-secondary mr-5 bi-dash" title="Delete Record" data-toggle="modal" data-target="#addModal" onClick="buttonActions(3);"></button>
                <input type="number" value="<?php echo $krudMsg; ?>" class="form-control form-control-sm text-center alert-secondary" style="width: 100px;" readonly>
            </div>
            <table class="table xtable-responsive xtable-striped table-sm">
                <thead class="bg-info pt-1 pb-1">
                    <?php echo $tHeadRow; ?>
                </thead>
                <tbody class="xalert-light">
                    <?php echo $tBodyRows; ?>
                </tbody>
                <tfoot class="bg-info pt-1 pb-1">
                    <?php echo $tFootRow; ?>
                </tfoot>
            </table>
        </div>

    </div>

    <!-- Modal -->
    <div class="modal fade" id="addModal" data-backdrop="static" data-keyboard="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header pb-1 pt-1 bg-info text-white">
                    <h5 class="modal-title" id="addModalLabel">No Record Action</h5>
                    <button type="button" class="close text-red" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body alert-dark shadow">
                    <div class="container-fluid">
                        <div class="alert justify-content-center xalert-light border">
                            <form id="inputForm" action="krud.php" method="post">
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label">UPN</label>
                                    <div class="col-sm-10">
                                        <input id="upnInput" class="form-control form-control-sm" type="number" name="PayrollNum" required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label">Name</label>
                                    <div class="col-sm-10">
                                        <input class="form-control form-control-sm" type="text" name="FullName">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label">Agenda</label>
                                    <div class="col-sm-10">
                                        <input class="form-control form-control-sm" type="text" name="Agenda">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label">Date</label>
                                    <div class="col-sm-10">
                                        <input class="form-control form-control-sm" type="date" name="DateVisited">
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-between pb-1 pt-1 alert-secondary">
                    <button type="button" class="btn btn-sm btn-outline-secondary bi-printer" data-dismiss="modal" id="closeButton" title="Print" onclick="window.print()"></button>
                    <button type=" button" class="btn btn-sm btn-outline-secondary" data-dismiss="modal" id="closeButton">X Cancel</button>
                    <button type="submit" class="btn btn-sm btn-outline-secondary bi-check2" name="saveButton" form="inputForm" id="saveButton"> No Action</button>
                </div>
            </div>
        </div>
    </div>
    <script src="/jquery/3/jquery.min.js"></script>
    <script src="/popper/2/popper.min.js"></script>
    <script src="/bootstrap/4/js/bootstrap.min.js"></script>
</body>

<script>
    var selectedUPN = 0;
    var selectedRow = null;

    $('#addModal').on('shown.bs.modal', function() {
        $('#upnInput').trigger('focus')
        //alert("The selected payroll number is " + selectedUPN);
    })

    function buttonActions(recAction) {
        if (recAction == 1) {
            document.getElementById('addModalLabel').innerHTML = 'Record will be Added';
            document.getElementById('saveButton').innerHTML = ' Save';
            return;
        }

        if (selectedUPN) { // If a record was selected
            if (recAction == 2) {
                document.getElementById('addModalLabel').innerHTML = '[ ' + selectedUPN + ' ] Record will be Changed';
                document.getElementById('saveButton').innerHTML = 'Save';
                getFormData();
            } else if (recAction == 3) {
                document.getElementById('addModalLabel').innerHTML = '[ ' + selectedUPN + ' ] Record will be Deleted';
                document.getElementById('saveButton').innerHTML = 'Delete';
            } else if (recAction == 4) {
                document.getElementById('addModalLabel').innerHTML = '[ ' + selectedUPN + ' ] Viewing Record';
                //document.getElementById('saveButton').disabled = true;
            } else {
                return false;
            }
        } else {
            document.getElementById('addModalLabel').innerHTML = '';
        }
    }

    function highlightOneRow(trId) {
        return;
        if (selectedRow) {
            selectedRow.className = ""; // Unselect the previous table-row
        }

        var rowCells = trId.getElementsByTagName("td");

        if (trId.className === "bg-primary text-light") {
            selectedRow = null; // Clear the row
            selectedUPN = 0; // Clear the selected payroll num
            trId.className = ""; // clear color
        } else {
            selectedRow = trId; // Remember the selected table-row
            selectedUPN = rowCells[1].firstChild.nodeValue; // Remember the selected UPN
            trId.className = "bg-primary text-light"; // Assign color
        }

    }

    function viewOneRecord(trId2) {
        //var rowCell1 = trId2.getElementById();
        alert('You have requested to view one record ' + trId2.firstChild.nodeValue);
    }

    function getFormData() {
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById("inputForm").innerHTML = this.responseText;
            }
        };
        xhttp.open("GET", "getformdata.php?q=" + selectedUPN, true);
        xhttp.send();
    }
</script>

</html>