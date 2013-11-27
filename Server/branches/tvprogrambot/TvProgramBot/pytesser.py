"""OCR in Python using the Tesseract engine from Google
http://code.google.com/p/pytesser/
by Michael J.T. O'Kelly
V 0.0.1, 3/10/07"""

import Image
import subprocess

from multiprocessing import current_process

import os

tesseract_exe_name = 'tesseract' # Name of executable to be called at command line
scratch_image_name = "/tmp/bot_%s_temp.bmp" % current_process().name # This file must be .bmp or other Tesseract-compatible format
scratch_text_name_root = "/tmp/bot_%s_temp" % current_process().name # Leave out the .txt extension
cleanup_scratch_flag = True  # Temporary files cleaned up after OCR operation

def call_tesseract(input_filename, output_filename, lang='eng'):
	"""Calls external tesseract.exe on input file (restrictions on types),
	outputting output_filename+'txt'"""
	args = [tesseract_exe_name, input_filename, output_filename, '-l', lang]
	proc = subprocess.Popen(args,stdout=subprocess.PIPE,stderr=subprocess.PIPE,close_fds=True)
	retcode = proc.wait()
	if retcode!=0:
		check_for_errors()

def image_to_string(im, cleanup = cleanup_scratch_flag, lang='eng'):
	"""Converts im to file, applies tesseract, and fetches resulting text.
	If cleanup=True, delete scratch files after operation."""
	try:
		image_to_scratch(im, scratch_image_name)
		call_tesseract(scratch_image_name, scratch_text_name_root, lang)
		text = retrieve_text(scratch_text_name_root)
	finally:
		if cleanup:
			perform_cleanup(scratch_image_name, scratch_text_name_root)
	return text

def image_file_to_string(filename, cleanup = cleanup_scratch_flag, graceful_errors=True, lang='eng'):
	"""Applies tesseract to filename; or, if image is incompatible and graceful_errors=True,
	converts to compatible format and then applies tesseract.  Fetches resulting text.
	If cleanup=True, delete scratch files after operation."""
	try:
		try:
			call_tesseract(filename, scratch_text_name_root)
			text = retrieve_text(scratch_text_name_root, lang)
		except Tesser_General_Exception:
			if graceful_errors:
				im = Image.open(filename)
				text = image_to_string(im, cleanup)
			else:
				raise
	finally:
		if cleanup:
			perform_cleanup(scratch_image_name, scratch_text_name_root)
	return text

def image_to_scratch(im, scratch_image_name):
	"""Saves image in memory to scratch file.  .bmp format will be read correctly by Tesseract"""
	im.save(scratch_image_name, dpi=(200,200))

def	retrieve_text(scratch_text_name_root):
	inf = file(scratch_text_name_root + '.txt')
	text = inf.read()
	inf.close()
	return text

def perform_cleanup(scratch_image_name, scratch_text_name_root):
	"""Clean up temporary files from disk"""
	for name in (scratch_image_name, scratch_text_name_root + '.txt', "tesseract.log"):
		try:
			os.remove(name)
		except OSError:
			pass

class Tesser_General_Exception(Exception):
	pass

class Tesser_Invalid_Filetype(Tesser_General_Exception):
	pass

def check_for_errors(logfile = "tesseract.log"):
	pass
#	inf = file(logfile)
#	text = inf.read()
#	inf.close()
	# All error conditions result in "Error" somewhere in logfile
#	if text.find("Error") != -1:
#		raise Tesser_General_Exception, text

if __name__=='__main__':
	im = Image.open('phototest.tif')
	text = image_to_string(im)
	print text
	try:
		text = image_file_to_string('fnord.tif', graceful_errors=False)
	except Tesser_General_Exception, value:
		print "fnord.tif is incompatible filetype.  Try graceful_errors=True"
		print value
	text = image_file_to_string('fnord.tif', graceful_errors=True)
	print "fnord.tif contents:", text
	text = image_file_to_string('fonts_test.png', graceful_errors=True)
	print text


