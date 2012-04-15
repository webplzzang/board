
var swfu = new Array();
function _init(base_url,pt,session,thumb,size_limit)
{
	var settings = {
		flash_url : base_url + "/js/swfupload/swfupload.swf",
		upload_url: base_url + "/board_file/upload_process",
		post_params: {
			"data[BoardFile][PHPSESSID]" : session,
			"data[BoardFile][pt]" : pt,
			"data[BoardFile][thumb]" : thumb
		},
		file_post_name: "data[BoardFile][add]",
		file_size_limit : size_limit,
		file_types : "*.*",
		file_types_description : "All Files",
		file_upload_limit : 100,
		file_queue_limit : 0,
		custom_settings : {
			progressTarget : "fsUploadProgress" + pt,
			cancelButtonId : "btnCancel"
		},
		debug: true,

		// Button settings
		button_image_url: base_url + "/js/swfupload/images/TestImageNoText_65x29.png",
		button_width: "65",
		button_height: "29",
		button_placeholder_id: "spanButtonPlaceHolder" + pt,
		button_text: '<span class="theFont">첨부</span>',
		button_text_style: ".theFont { font-size: 16; }",
		button_text_left_padding: 12,
		button_text_top_padding: 3,
		
		// The event handler functions are defined in handlers.js
		file_queued_handler : fileQueued,
		file_queue_error_handler : fileQueueError,
		file_dialog_complete_handler : fileDialogComplete,
		upload_start_handler : uploadStart,
		upload_progress_handler : uploadProgress,
		upload_error_handler : uploadError,
		upload_success_handler : uploadSuccess,
		upload_complete_handler : uploadComplete,
		queue_complete_handler : queueComplete	// Queue plugin event
	};

	swfu[pt] = new SWFUpload(settings);
}