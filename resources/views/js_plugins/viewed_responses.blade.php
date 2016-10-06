<script>
function acceptedListViewed() {
	$.ajax({
      type: "POST",
      url: "/set-as-viewed",
      data: {
      			'viewed': 'accepted',
                '_token': '{!! csrf_token() !!}'
            }
    })
}

function rejectedListViewed() {
	$.ajax({
      type: "POST",
      url: "/set-as-viewed",
      data: {
      			'viewed' : 'rejected',
                '_token': '{!! csrf_token() !!}'
            }
    })
}
</script>