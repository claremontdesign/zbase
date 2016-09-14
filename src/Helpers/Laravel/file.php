<?php

/**
 * Zbase-Laravel Helpers-File/Directories
 *
 * Functions and Helpers for File and Directories manipulation
 *
 * @link http://zbase.dennesabing.com
 * @author Dennes B Abing <dennes.b.abing@gmail.com>
 * @license proprietary
 * @copyright Copyright (c) 2016 ClaremontDesign/MadLabs-Dx
 * @file file.php
 * @project Zbase
 * @package Zbase/Laravel/Helpers
 */

/**
 * Return the Path to zbase package
 * @return string
 */
function zbase_path()
{
	return zbase()->path();
}

/**
 * Path to zbase library
 * @return string
 */
function zbase_path_library($path)
{
	return zbase_path() . 'library/' . $path;
}

/**
 * Return the Asset base path
 * @return string
 */
function zbase_path_asset($path = null, $absolute = false)
{
	return (!empty($absolute) ? zbase_url_root() : null) . '/' . zbase_tag() . '/assets/' . $path;
}

/**
 * Return the path to theme.
 *
 * @param string $theme
 * @param string $section
 * @return string
 */
function zbase_path_asset_theme($theme, $section)
{
	return 'templates/' . $section . '/' . $theme . '/';
}

/**
 * Return the package-based asset path
 *
 * @param string $package
 * @return string
 */
function zbase_path_asset_package($package)
{
	return 'packages/' . $package . '/';
}

/**
 * Return the package-theme-based asset path
 *
 * @param string $package
 * @param string $theme
 * @param string $section
 * @return type
 */
function zbase_path_asset_package_theme($package, $theme, $section)
{
	return 'packages/' . $package . '/templates/' . $section . '/' . $theme . '/';
}

/**
 * Application path
 * return laravel\app
 *
 * @return string
 */
function zbase_app_path($path = null)
{
	return app_path($path);
}

/**
 * Application base Path
 * return laravel\
 *
 * @return string
 */
function zbase_base_path($path = null)
{
	return base_path($path);
}

/**
 * Return the Public Path
 * return laravel\public
 *
 * @return string
 */
function zbase_public_path($path = null)
{
	$pubPath = env('ZBASE_PUBLIC_PATH');
	if(empty($pubPath))
	{
		return public_path($path);
	}
	return $pubPath . $path;
}

/**
 * Zbase Public Download Folder
 */
function zbase_public_download_folder()
{
	$folder = zbase_public_path() . '/zbase_tmp/downloads/';
	zbase_directory_check($folder, true);
	return $folder;
}

/**
 * Zbase Public Image Folder
 */
function zbase_public_image_folder()
{
	$folder = zbase_public_path() . '/zbase_tmp/img/';
	zbase_directory_check($folder, true);
	return $folder;
}

/**
 * Zbase Public Image Folder
 */
function zbase_public_image_link($file)
{
	return '/zbase_tmp/downloads/' . basename($file);
}

/**
 * Zbase Public Download Folder
 */
function zbase_public_download_link($file)
{
	return '/zbase_tmp/downloads/' . $file;
}

/**
 * Return the Storage Path
 * return laravel\storage
 *
 * @return string
 */
function zbase_storage_path($path = null)
{
	return storage_path($path);
}

/**
 * Return TMP Folder
 * @param type $path
 */
function zbase_tmp_path($path = null)
{
	$tmpPath = zbase_storage_path() . '/tmp/' . $path;
	zbase_directory_check($tmpPath, true);
	return $tmpPath;
}

/**
 * Check if directory exists, else create it
 * @param string $path
 * @param boolean $create
 * @return string|false
 */
function zbase_directory_check($path, $create = false)
{
	if(empty($create))
	{
		return is_dir($path);
	}
	if(!is_dir($path))
	{
		if($create)
		{
			mkdir($path, 0777, true);
			return true;
		}
	}
	return false;
}

/**
 * Return all directories/folders from a given path
 * @param string $path Path to folder/directory
 * @return array|null
 */
function zbase_directories($path)
{
	if(zbase_directory_check($path))
	{
		return \File::directories($path);
	}
	return null;
}

/**
 * Return all files inside a directory/folder
 * @param string $path Path to folder/directory
 * @param boolean $recursive If to return all files recursively; default: false
 * @return array|null
 */
function zbase_directory_files($path, $recursive = false)
{
	if(zbase_directory_check($path))
	{
		if(!empty($recursive))
		{
			return \File::allFiles($path);
		}
		return \File::files($path);
	}
	return null;
}

/**
 * Copy directory to another directory
 * @param string $src
 * @param string $dst
 * @param array|mixed $options
 */
function zbase_directory_copy($src, $dst, $options = null)
{
	\File::copyDirectory($src, $dst, $options);
}

/**
 * Recursively remove folder
 * @param string $dir
 * @param boolean $preserve if not delete the folder, just clean up
 */
function zbase_directory_remove($dir, $preserve = false)
{
	\File::deleteDirectory($dir, $preserve);
}

/**
 * Create a file name from file using the $file extension
 * @param string $file The file path
 * @param string $fileName the new file naame without the extension
 * @return string
 */
function zbase_file_name_from_file($file, $fileName, $isUpload = false)
{
	if($isUpload)
	{
		$file = explode('.', $file);
		if(!empty($file[count($file) - 1]))
		{
			return $fileName . '.' . $file[count($file) - 1];
		}
	}
	if(file_exists($file))
	{
		$f = new \SplFileInfo($file);
		return $fileName . '.' . $f->getExtension();
	}
	return false;
}

/**
 * Upload a file
 * @param string $index The form Index
 * @param string $folder the Folder to save the new file
 * @param string $newFilename the new filename (just filename no folder)
 * @param string $encodingFormat The Format the file to be encoded. jpg, png
 * @param array $size The Size to encode [$width, $height], [$width, null]
 * @return string The Path to the new file
 */
function zbase_file_upload_image($index, $folder, $newFilename, $encodingFormat = 'jpg', $size = [])
{
	/**
	 * Using angular flow.js
	 * https://github.com/flowjs/flow-php-server
	 *
	  "flowChunkNumber" => "1"
	  "flowChunkSize" => "1048576"
	  "flowCurrentChunkSize" => "83167"
	  "flowTotalSize" => "83167"
	  "flowIdentifier" => "83167-Avatar2jpg"
	  "flowFilename" => "Avatar2.jpg"
	  "flowRelativePath" => "Avatar2.jpg"
	  "flowTotalChunks" => "1"
	 *
	 */
	$tmpFolder = zbase_tmp_path();
	$newFile = $folder . str_replace(array('.png', '.jpg', '.gif', '.bmp', '.jpeg'), '.' . $encodingFormat, $newFilename);
	if(!empty(zbase_request_query_input('flowChunkNumber', false)))
	{
		if(\Flow\Basic::save($newFile, $tmpFolder))
		{
			$im = \Image::make($newFile);
		}
		else
		{
			// zbase()->json()->setVariable('files', $_FILES);
			// zbase()->json()->setVariable('success', false);
			// zbase()->json()->setVariable('get', $_GET);
			// zbase()->json()->setVariable('post', $_POST);
			return zbase_abort(204);
		}
	}
	if(!empty($_FILES[$index]['tmp_name']))
	{
		zbase_directory_check($folder, true);
		if(class_exists('\Image'))
		{
			$im = \Image::make($_FILES[$index]['tmp_name']);
		}
		else
		{
			if(move_uploaded_file($_FILES[$index]['tmp_name'], $newFile))
			{
				return $newFile;
			}
		}
	}
	if(!empty($im))
	{
		if(!empty($size))
		{
			$im->resize($size[0], $size[1], function ($constraint) {
				$constraint->aspectRatio();
			});
		}
		$im->encode($encodingFormat, 100)->save($newFile);
		return $newFile;
	}
	return false;
}

/**
 * Return the file mime type
 * @param string $file The File to question
 * @return string
 */
function zbase_file_mime_type($file)
{
	if(zbase_file_exists($file))
	{
		if(class_exists('\Image'))
		{
			return \Image::make($file)->mime();
		}
		else
		{
			return image_type_to_mime_type(exif_imagetype($file));
		}
	}
	return null;
}

/**
 * eturns the size of the image file in bytes or false if image instance is not created from a file.
 * @param string $file The File to question
 * @return boolean|integer
 */
function zbase_file_size($file)
{
	if(zbase_file_exists($file))
	{
		if(exif_imagetype($file))
		{
			if(class_exists('\Image'))
			{
				return \Image::make($file)->filesize();
			}
			return filesize($file);
		}
	}
	return null;
}

/**
 * Serve file/image publicly
 * Will create a file in the public folder and serve the public file
 * @param string $path Path to file original
 * @param int $width
 * @param int $height
 * @param int $quality
 * @param boolean $download
 *
 * @return array [src => '',size => '', mime => '']
 */
function zbase_file_serve_image($path, $width, $height, $quality = 80, $download = false)
{
	if(file_exists($path))
	{
		$info = $path !== null ? getimagesize($path) : getimagesizefromstring($path);
		zbase_directory_check(zbase_storage_path('tmp/images'), true);
		$newFile = zbase_storage_path('tmp/images') . '/' . md5(basename($path) . '_' . $width . 'x' . $height);
		switch ($info[2])
		{
			case IMAGETYPE_JPEG:
				$newFile = $newFile . '.jpg';
				break;
			case IMAGETYPE_GIF:
				$newFile = $newFile . '.gif';
				break;
			case IMAGETYPE_PNG:
				$newFile = $newFile . '.png';
				break;
			default: return false;
		}
		if(!file_exists($newFile))
		{
			copy($path, $newFile);
			zbase_file_image_resize($newFile, null, $width, $height, true, 'file', true, false, $quality);
		}
		$mime = image_type_to_mime_type($info[2]);
		$size = filesize($newFile);
		return ['src' => $newFile, 'size' => $size, 'mime' => $mime];
	}
}

/**
 * Return file mimetype
 *
 * @return boolean
 */
function zbase_file_mimetype($path)
{
	$info = $path !== null ? getimagesize($path) : getimagesizefromstring($path);
	$mime = image_type_to_mime_type($info[2]);
	return $mime;
}

/**
 * easy image resize function
 * @param  $file - file name to resize
 * @param  $string - The image data, as a string
 * @param  $width - new image width
 * @param  $height - new image height
 * @param  $proportional - keep image proportional, default is no
 * @param  $output - name of the new file (include path if needed)
 * @param  $delete_original - if true the original image will be deleted
 * @param  $use_linux_commands - if set to true will use "rm" to delete the image, if false will use PHP unlink
 * @param  $quality - enter 1-100 (100 is best quality) default is 100
 * @return boolean|resource
 */
function zbase_file_image_resize($file, $string = null, $width = 0, $height = 0, $proportional = false, $output = 'file', $delete_original = true, $use_linux_commands = false, $quality = 100)
{
	if($height <= 0 && $width <= 0)
	{
		return false;
	}
	if($file === null && $string === null)
	{
		return false;
	}
	# Setting defaults and meta
	$info = $file !== null ? getimagesize($file) : getimagesizefromstring($string);
	$image = '';
	$final_width = 0;
	$final_height = 0;
	list($width_old, $height_old) = $info;
	$cropHeight = $cropWidth = 0;

	# Calculating proportionality
	if($proportional)
	{
		if($width == 0)
		{
			$factor = $height / $height_old;
		}
		elseif($height == 0)
		{
			$factor = $width / $width_old;
		}
		else
		{
			$factor = min($width / $width_old, $height / $height_old);
		}
		$final_width = round($width_old * $factor);
		$final_height = round($height_old * $factor);
	}
	else
	{
		$final_width = ( $width <= 0 ) ? $width_old : $width;
		$final_height = ( $height <= 0 ) ? $height_old : $height;
		$widthX = $width_old / $width;
		$heightX = $height_old / $height;

		$x = min($widthX, $heightX);
		$cropWidth = ($width_old - $width * $x) / 2;
		$cropHeight = ($height_old - $height * $x) / 2;
	}

	# Loading image to memory according to type
	switch ($info[2])
	{
		case IMAGETYPE_JPEG: $file !== null ? $image = imagecreatefromjpeg($file) : $image = imagecreatefromstring($string);
			break;
		case IMAGETYPE_GIF: $file !== null ? $image = imagecreatefromgif($file) : $image = imagecreatefromstring($string);
			break;
		case IMAGETYPE_PNG: $file !== null ? $image = imagecreatefrompng($file) : $image = imagecreatefromstring($string);
			break;
		default: return false;
	}


	# This is the resizing/resampling/transparency-preserving magic
	$image_resized = imagecreatetruecolor($final_width, $final_height);
	if(($info[2] == IMAGETYPE_GIF) || ($info[2] == IMAGETYPE_PNG))
	{
		$transparency = imagecolortransparent($image);
		$palletsize = imagecolorstotal($image);

		if($transparency >= 0 && $transparency < $palletsize)
		{
			$transparent_color = imagecolorsforindex($image, $transparency);
			$transparency = imagecolorallocate($image_resized, $transparent_color['red'], $transparent_color['green'], $transparent_color['blue']);
			imagefill($image_resized, 0, 0, $transparency);
			imagecolortransparent($image_resized, $transparency);
		}
		elseif($info[2] == IMAGETYPE_PNG)
		{
			imagealphablending($image_resized, false);
			$color = imagecolorallocatealpha($image_resized, 0, 0, 0, 127);
			imagefill($image_resized, 0, 0, $color);
			imagesavealpha($image_resized, true);
		}
	}
	imagecopyresampled($image_resized, $image, 0, 0, $cropWidth, $cropHeight, $final_width, $final_height, $width_old - 2 * $cropWidth, $height_old - 2 * $cropHeight);


	# Taking care of original, if needed
	if($delete_original)
	{
		if($use_linux_commands)
		{
			exec('rm ' . $file);
		}
		else
		{
			@unlink($file);
		}
	}

	# Preparing a method of providing result
	switch (strtolower($output))
	{
		case 'browser':
			$mime = image_type_to_mime_type($info[2]);
			header("Content-type: $mime");
			$output = NULL;
			break;
		case 'file':
			$output = $file;
			break;
		case 'return':
			return $image_resized;
			break;
		default:
			break;
	}

	# Writing image according to type to the output destination and image quality
	switch ($info[2])
	{
		case IMAGETYPE_GIF: imagegif($image_resized, $output);
			break;
		case IMAGETYPE_JPEG: imagejpeg($image_resized, $output, $quality);
			break;
		case IMAGETYPE_PNG:
			$quality = 9 - (int) ((0.9 * $quality) / 10.0);
			imagepng($image_resized, $output, $quality);
			break;
		default: return false;
	}
	return true;
}
