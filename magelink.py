#!/usr/bin/env python
import glob
import os
import subprocess

from argparse import ArgumentParser

parser = ArgumentParser()

parser.add_argument("magento_path", help="Path to the Magento installation")

options = parser.parse_args()

print options.magento_path

current_dir = os.getcwd()

if not os.path.exists("%s/app/code/local" % options.magento_path):
	os.mkdir("%s/app/code/local" % options.magento_path)

def symlinker(source, dest):
	if os.path.isdir(source):
		for src in glob.iglob(source + "/*"):
			destf = dest + '/' + os.path.basename(src)
			if not os.path.exists(destf):
				os.symlink(src, destf)
				print "Linked %s to\n\t%s" % (destf, src)
	else:
		os.symlink(source, dest)
		print "Linked %s to\n\t%s" % (dest, source)
	return


symlinker("%s/app/code/local" % current_dir, "%s/app/code/local/" % options.magento_path)
symlinker("%s/app/design/frontend/base/default/layout" % current_dir, "%s/app/design/frontend/base/default/layout/" % options.magento_path)
symlinker("%s/app/design/frontend/base/default/template" % current_dir, "%s/app/design/frontend/base/default/template/" % options.magento_path)
symlinker("%s/app/etc/modules" % current_dir, "%s/app/etc/modules/" % options.magento_path)
os.symlink("%s/vendor/janrain/plex/src/janrain" % current_dir, "%s/lib/janrain" % options.magento_path)
