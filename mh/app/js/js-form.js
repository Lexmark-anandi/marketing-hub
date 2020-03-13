function sendFormProfile(){
	waiting('body');
	
	$('#formProfile').ajaxSubmit({
		url: objSystem.directoryInstallation + objSystem.pathFunctionsApp + 'fu-profile-update.php',
		clearForm: false, 
		type: 'post', 
		headers: {
			csrfToken: Cookies.get('csrf')
		},
		success: function(result, status, jqXHR){
			actualizeStatus(result, status);
			
			$('#formProfile .formmessage').html(result);
			
			unwaiting();
		}
	});
}







var myDropzone = '';

function initLogoUpload(){
	Dropzone.autoDiscover = false;
	$('#partner_logo').dropzone({
		url: objSystem.directoryInstallation + objSystem.pathFunctionsApp + 'fu-profile-logo-update.php',
		paramName: 'partner_logo',
		maxFiles: 1,
		filesizeBase: 1024,
		autoProcessQueue: false,
		createImageThumbnails: false,
		dictDefaultMessage: '<p style="text-align:center">' + objText.logoUpload1 + '</p><ul class="uploadRequirements"><li>' + objText.logoErrorExtension + '</li><li>' + objText.logoErrorSize + '</li><li>' + objText.logoErrorDim + '</li></ul>',
		init: function() {
			myDropzone = this;
			
			this.on('addedfile', function(file){ 
				var errorMessage = '';
				$('#profileLogo .formmessage').html('');
				
				// check extension
				var aAllowedTypes = allowedTypesLogo.split(',');
				var errorType = 1;
				var filetype = file.name.split('.').pop();
				for(key in aAllowedTypes){
					if(filetype.toLowerCase() == aAllowedTypes[key].toLowerCase()) errorType = 0;
				}
				if(errorType == 1) errorMessage += '<li>' + objText.logoErrorExtension + '</li>';
				
				// check max size
				var errorSize = 0;
				var maxsize = parseFilesize(maxFilesizeLogo);
				if(maxsize < file.size){
					errorSize = 1;
					errorMessage += '<li>' + objText.logoErrorSize + '</li>';
				}
				
				// check min dimension
				if(errorType == 0){
					var errorDim = 0;
					var reader = new FileReader();
					reader.readAsDataURL(file);
					reader.onload = function (e) {
						var image = new Image();
						image.src = e.target.result;
						image.onload = function () {
							var height = this.height;
							var width = this.width;
							if(width < minDimensionLogo && height < minDimensionLogo){
								errorDim = 1;
								errorMessage += '<li>' + objText.logoErrorDim + '</li>';
							}
	
							if(errorMessage == ''){
								// process file
								myDropzone.processQueue();
							}else{
								// error message
								errorMessage = '<p>' + objText.logoError1 + '</p><ul class="uploadRequirements">' + errorMessage + '</ul><p>' + objText.logoError2 + '</p>';
								$('#profileLogo .formmessage').html('<div class="formmessageError">' + errorMessage + '</div>');
								myDropzone.removeAllFiles();
						
							}
						}
					}
				}else{
					errorMessage = '<p>' + objText.logoError1 + '</p><ul class="uploadRequirements">' + errorMessage + '</ul><p>' + objText.logoError2 + '</p>';
					$('#profileLogo .formmessage').html('<div class="formmessageError">' + errorMessage + '</div>');
					myDropzone.removeAllFiles();
			
				}
			});
			this.on("processing", function(file, progress, bytesSent){ 
				$('.dropzoneUploadFile').html(file.name);
			});
			this.on("uploadprogress", function(file, progress, bytesSent){ 
				$('.dropzoneUploadProgress').css('width', progress+'%');
			});
			this.on("success", function(file, response){ 
				$('#logo_thumbnail').html(response);
				myDropzone.removeAllFiles();
			});
		},
		previewTemplate: '<div class="dropzoneUploadOuter"><div class="dropzoneUploadProgress"></div><div class="dropzoneUploadFile"></div></div>'
	});

}




function downloadMedia(file, filename, folder, type){
	$('<form action="' + objSystem.directoryInstallation + objSystem.pathFunctionsApp + 'fu-download.php" method="post" class="formDownload"><input type="hidden" value="' + file + '" name="file" id="file" ><input type="hidden" value="' + filename + '" name="filename" id="filename" ><input type="hidden" value="' + type + '" name="type" id="type" ><input type="hidden" value="' + folder + '" name="folder" id="folder" ></form>').appendTo('body').submit();

	window.setTimeout(function(){$('.formDownload').remove()},1000); 
}



