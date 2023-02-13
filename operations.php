<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>CRUD USING AJAX</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <link rel="stylesheet" href="css/dataTables.bootstrap.min.css">
    <link rel="stylesheet" href="styler.css">


<script src="https://kit.fontawesome.com/a076d05399.js"></script>
</head>

<body>
  <nav class="navbar">
    <div class="content">
      <div class="logo"><a href="index.html">CRUD</a></div>
      <ul class="menu-list">
        <div class="icon cancel-btn">
          <i class="fas fa-times"></i>
        </div>
        <li><a href="index.html">Home</a></li>
        <li><a href="#">About</a></li>
        <li><a href="calendar.php">Event Planner</a></li>
        <li><a href="operations.php">Operation Manager</a></li>
        
      </ul>
      <div class="icon menu-btn">
        <i class="fas fa-bars"></i>
      </div>
    </div>
  </nav>
    <div class="container"  style="top:80px;margin-top: 30px;">

        <div id="tableManager" class="modal fade">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h2 class="modal-title">Company Name</h2>
                    </div>

                    <div class="modal-body">
                        <div id="editContent">
                            <input type="text" class="form-control" placeholder="Company Name..." id="countryName"><br>
                            <textarea class="form-control" id="shortDesc" placeholder="Address.."></textarea><br>
                            <textarea class="form-control" id="longDesc" placeholder="Operation.."></textarea><br>
                            <input type="hidden" id="editRowID" value="0">
                        </div>

                        <div id="showContent" style="display:none;">
                            <h3>Short Description</h3>
                            <div id="shortDescView"></div>
                            <hr>
                            <h3>Long Description</h3>
                            <div id="longDescView" style="overflow-y: scroll; height: 300px;"></div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <input type="button" class="btn btn-primary" data-dismiss="modal" value="Close" id="closeBtn" style="display: none;">
                        <input type="button" id="manageBtn" onclick="manageData('addNew'); document.location.reload();" value="Save" class="btn btn-success">
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <h2>Operations Manager</h2>

                <input style="float: right" type="button" class="btn btn-success" id="addNew" value="Add New">
                <br><br>
                <table class="table table-hover table-bordered">
                    <thead>
                        <tr>
                            <td>ID</td>
                            <td>Company Name</td>
                            <td>Address</td>
                            <td>Options</td>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>

            </div>
        </div>
    </div>

    <script src="http://code.jquery.com/jquery-3.2.1.min.js" integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4=" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
    <script src="js/jquery.dataTables.min.js" type="text/javascript"></script>
    <script src="js/dataTables.bootstrap.min.js" type="text/javascript"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            $("#addNew").on('click', function () {
               $("#tableManager").modal('show');
            });

            $("#tableManager").on('hidden.bs.modal', function () {
               $("#showContent").fadeOut();
               $("#editContent").fadeIn();
               $("#editRowID").val(0);
               $("#longDesc").val("");
               $("#shortDesc").val("");
               $("#countryName").val("");
               $("#closeBtn").fadeOut();
               $("#manageBtn").attr('value', 'Add New').attr('onclick', "manageData('addNew')").fadeIn();
            });

            getExistingData(0, 50);
        });

        function deleteRow(rowID) {
            if (confirm('Are you sure??')) {
                $.ajax({
                    url: 'ajax.php',
                    method: 'POST',
                    dataType: 'text',
                    data: {
                        key: 'deleteRow',
                        rowID: rowID
                    }, success: function (response) {
                        $("#country_"+rowID).parent().remove();
                        alert(response);
                    }
                });
            }
        }

        function viewORedit(rowID, type) {
            $.ajax({
                url: 'ajax.php',
                method: 'POST',
                dataType: 'json',
                data: {
                    key: 'getRowData',
                    rowID: rowID
                }, success: function (response) {
                    if (type == "view") {
                        $("#showContent").fadeIn();
                        $("#editContent").fadeOut();
                        $("#longDescView").html(response.longDesc);
                        $("#shortDescView").html(response.shortDesc);
                        $("#manageBtn").fadeOut();
                        $("#closeBtn").fadeIn();
                    } else {
                        $("#editContent").fadeIn();
                        $("#editRowID").val(rowID);
                        $("#showContent").fadeOut();
                        $("#longDesc").val(response.longDesc);
                        $("#shortDesc").val(response.shortDesc);
                        $("#countryName").val(response.countryName);
                        $("#closeBtn").fadeOut();
                        $("#manageBtn").attr('value', 'Save Changes').attr('onclick', "manageData('updateRow')");
                    }

                    $(".modal-title").html(response.countryName);
                    $("#tableManager").modal('show');
                }
            });
        }

        function getExistingData(start, limit) {
            $.ajax({
                url: 'ajax.php',
                method: 'POST',
                dataType: 'text',
                data: {
                    key: 'getExistingData',
                    start: start,
                    limit: limit
                }, success: function (response) {
                    if (response != "reachedMax") {
                        $('tbody').append(response);
                        start += limit;
                        getExistingData(start, limit);
                    } else
                        $(".table").DataTable();
                }
            });
        }

        function manageData(key) {
            var name = $("#countryName");
            var shortDesc = $("#shortDesc");
            var longDesc = $("#longDesc");
            var editRowID = $("#editRowID");

            if (isNotEmpty(name) && isNotEmpty(shortDesc) && isNotEmpty(longDesc)) {
                $.ajax({
                   url: 'ajax.php',
                   method: 'POST',
                   dataType: 'text',
                   data: {
                       key: key,
                       name: name.val(),
                       shortDesc: shortDesc.val(),
                       longDesc: longDesc.val(),
                       rowID: editRowID.val()
                   }, success: function (response) {
                       if (response != "success")
                           alert(response);
                       else {
                           $("#country_"+editRowID.val()).html(name.val());
                           name.val('');
                           shortDesc.val('');
                           longDesc.val('');
                           $("#tableManager").modal('hide');
                           $("#manageBtn").attr('value', 'Add').attr('onclick', "manageData('addNew')");
                       }
                   }
                });
            }
        }

        function isNotEmpty(caller) {
            if (caller.val() == '') {
                caller.css('border', '1px solid red');
                return false;
            } else
                caller.css('border', '');

            return true;
        }



    </script>


    <script>
      const body = document.querySelector("body");
      const navbar = document.querySelector(".navbar");
      const menu = document.querySelector(".menu-list");
      const menuBtn = document.querySelector(".menu-btn");
      const cancelBtn = document.querySelector(".cancel-btn");
      menuBtn.onclick = ()=>{
        menu.classList.add("active");
        menuBtn.classList.add("hide");
        cancelBtn.classList.add("show");
        body.classList.add("disabledScroll");
      }
      cancelBtn.onclick = ()=>{
        menu.classList.remove("active");
        menuBtn.classList.remove("hide");
        cancelBtn.classList.remove("show");
        body.classList.remove("disabledScroll");
      }
    
      window.onscroll = ()=>{
        this.scrollY > 20 ? navbar.classList.add("sticky") : navbar.classList.remove("sticky");
      }
    </script>
</body>
</html>