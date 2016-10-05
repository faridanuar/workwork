<script>
Dropzone.options.addPhotosForm = {

	paramName: 'photo', // input name / id

	maxFilesize: 0.5, // mb

	acceptedFiles: '.jpg, .jpeg, .png', // allowed files

	accept: function(file, done) {
	    console.log("uploaded");
	    done();
	},
	init: function() {
	    this.on("addedfile", function() {
			if (this.files[1]!=null){
				this.removeFile(this.files[0]);
			}
	    });
	}
};
</script>