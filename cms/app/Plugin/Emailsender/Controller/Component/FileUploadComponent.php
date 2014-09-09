<?php
class FileUploadComponent extends Object{ 

/**
 * uploads files to the server for attachments
 * @params:
 *		$folder 		= the folder to upload the files e.g. 'img/files'
 *		$formdata	= the array containing the form files
 *		$itemId 		= id of the item (optional) will create a new sub folder
 * @return:
 *		will return an array with the success of each file upload
 */

function uploadFiles($folder, $formdata, $itemId = null) {

	// setup dir names absolute and relative

	App::import('Core', 'Folder');

	 $folder_url = WWW_ROOT.'files'.DS.$folder;
	 //$folder_url = '../'.$folder;

	 $rel_url = $folder;

	

	// create the folder if it does not exist

	if(!is_dir($folder_url)) {

		$folder_url = new Folder($folder_url, true);
		$folder_url = $folder_url->pwd();
		//mkdir($folder_url);

	}

	

	// if itemId is set create an item folder

	if($itemId) {

		// set new absolute folder

		$folder_url = WWW_ROOT.$folder.DS.$itemId; 
		//$folder_url = ROOT.$folder.'/'.$itemId; 

		// set new relative folder

		$rel_url = $folder.DS.$itemId;
		//$rel_url = $folder.'/'.$itemId;

		// create directory

		if(!is_dir($folder_url)) {

			$folder_url = new Folder($folder_url, true);
			$folder_url = $folder_url->pwd();
			//mkdir($folder_url);

		}

	}

	

	// list of permitted file types, this is only images but documents can be added

	$permitted = array('application/vnd.ms-excel','application/msword','application/vnd.openxmlformats-officedocument.wordprocessingml.document','application/pdf', 'application/rtf', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');

	

	// loop through and deal with the files

	

	foreach($formdata as $file) 

	{

	// replace spaces with underscores

		$filename = str_replace(' ', '_', $file['name']);

		// assume filetype is false

		$typeOK = false;

		$file['type'];

		// check filetype is ok

		foreach($permitted as $type) 

		{

			if($type == $file['type']) 

			{

				$typeOK = true;

				break;

			}

		}

		// if file type ok upload the file
		
		if($typeOK) 

		{

			// switch based on error code

			switch($file['error']) 

			{

				case 0:

					// check filename already exists

					if(!file_exists($rel_url.DS.$filename)) 
					//if(!file_exists($rel_url.'/'.$filename)) 

					{

						// create full filename

						$full_url = $folder_url.DS.$filename;
						//$full_url = $folder_url.'/'.$filename;

						$url = $rel_url.DS.$filename;
						//$url = $rel_url.'/'.$filename;

						// upload the file

						$success = move_uploaded_file($file['tmp_name'], $url);

					} 

					else 

					{

						// create unique filename and upload file

						ini_set('date.timezone', 'Europe/London');

						$now = date('Y-m-d-His');

						$full_url = $folder_url.DS.$now.$filename;
						//$full_url = $folder_url.'/'.$now.$filename;

						$url = $rel_url.DS.$now.$filename;
						//$url = $rel_url.'/'.$now.$filename;

						$success = move_uploaded_file($file['tmp_name'], $url);

					}

					// if upload was successful
					
					if($success) 

					{
						$filename = explode('/',$url); 
						$uploadedFileName =$filename[1];
						$result['filename'][] = $uploadedFileName;

						// save the url of the file

						$result['urls'][] = $url;

					} 

					else 

					{

						$result['errors'][] = "Error uploaded $filename. Please try again.";

					}

					break;

				case 3:

					// an error occured

					$result['errors'][] = "Error uploading $filename. Please try again.";

					break;

				default:

					// an error occured

					$result['errors'][] = "System error uploading $filename. Contact webmaster.";

					break;

			}

		} 

		elseif($file['error'] == 4) 

		{

			// no file was selected for upload

			$result['nofiles'][] = "No file Selected";
		} 
		else 

		{
			// unacceptable file type

			$result['errors'][] = "$filename cannot be uploaded. Acceptable file types: doc,docx,pdf,rtf,xls,xlsx.";

		}
	}
	return $result;
}

}
?>