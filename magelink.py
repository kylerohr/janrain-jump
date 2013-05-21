#!/usr/bin/env python

import os
import subprocess
from subprocess import call

from argparse import ArgumentParser

parser = ArgumentParser()

parser.add_argument("magento_path", help="Path to the Magento installation")

options = parser.parse_args()

print options.magento_path

current_dir = os.getcwd()

if not os.path.exists("%s/app/code/local" % options.magento_path):
	call(["mkdir", "%s/app/code/local" % options.magento_path])

call(["ln", "-sf", "%s/app/code/local/Janrain" % current_dir, "%s/app/code/local/Janrain" % options.magento_path])
call(["ln", "-sf", "%s/app/design/frontend/base/default/layout/*" % current_dir, "%s/app/design/frontend/base/default/layout/*" % options.magento_path])
call(["ln", "-sf", "%s/app/design/frontend/base/default/template/*" % current_dir, "%s/app/design/frontend/base/default/template/*" % options.magento_path])
call(["ln", "-sf", "%s/app/etc/modules/*" % current_dir, "%s/app/etc/modules/*" % options.magento_path])
