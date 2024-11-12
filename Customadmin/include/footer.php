
<footer class="main-footer text-center">
    <strong>Copyright © Custom Portal INC. All Rights Reserved.</strong>
</footer>
</div>
<script src="js/jquery-ui.min.js"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
    $.widget.bridge('uibutton', $.ui.button);
</script>
<script src="js/bootstrap.min.js"></script>
<script src="js/moment.min.js"></script>
<script src="js/jquery.slimscroll.min.js"></script>
<script src="js/main.min.js"></script>
 <script src="js/jquery.dataTables.min.js"></script>
 <script src="js/dataTables.bootstrap.min.js"></script>
 <script type="text/javascript">
  $(function () {          
			$('#no_search').DataTable({
			"paging":   false,
			"ordering": false,
			"info":     false,
			"searching": false
			});
			$('#search_table').DataTable();
			
			
  });
 </script>
</body>
</html>