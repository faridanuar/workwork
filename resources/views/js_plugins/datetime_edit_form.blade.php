<script type="text/javascript">
var startDate = new Date('{{ $startDate }}');
var endDate = new Date('{{ $endDate }}');
    $(function () {
    	$('#datetimepicker1').datetimepicker({
            format: 'L',
            minDate: startDate,
        });
    	$('#datetimepicker2').datetimepicker({
            format: 'L',
            minDate: endDate,
        });
        $('#datetimepicker3').datetimepicker({
            format: 'LT'
        });
        $('#datetimepicker4').datetimepicker({
            format: 'LT'
        });
    });
</script>