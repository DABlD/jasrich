<script>
function toggleSidebar(ref){
  document.getElementById("sidebar").classList.toggle("active");
  ref.classList.toggle("active");
}

    $('[data-toggle="tooltip"]').tooltip();
    $('.panel-body').height($('.sidebar').height());
    $('.salesAnalysis').height($('.sidebar').height()/2);
</script>

</body>
</html>