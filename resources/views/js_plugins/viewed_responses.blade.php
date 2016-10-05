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