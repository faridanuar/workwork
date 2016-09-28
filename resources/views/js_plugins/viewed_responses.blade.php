<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
<script>
function setAsViewed() {
	$.ajax({
      type: "POST",
      url: "/set-as-viewed",
      data: {
                '_token': '{!! csrf_token() !!}'
            }
    })
}

</script>