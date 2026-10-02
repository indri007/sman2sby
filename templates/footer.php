</div>

<aside class="control-sidebar control-sidebar-dark">
</aside>
</div>

<script src="assets/js/adminlte.min.js"></script>
<script src="assets/js/demo.js"></script>
<script>
  $(function() {
    $('#example2').DataTable({
      "paging": true,
      "lengthChange": true,
      "searching": true,
      "ordering": false,
      "info": true,
      "autoWidth": false,
      "responsive": true,
    });
    $('#example3').DataTable({
      "paging": true,
      "lengthChange": false,
      "searching": false,
      "ordering": false,
      "info": false,
      "autoWidth": false,
      "responsive": false,
    });
  });
</script>
</body>

</html>