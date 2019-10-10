#!/usr/bin/env python

import argparse
import os
import sys
import errno
from shutil import copyfile, copytree

class Snippet:

    def __init__(self, lines, index):
        parts = lines[index].split()
        self.command = parts[1]
        if self.command == "new":
            self.filename = parts[2]
            self.contents = getSnippetContents(lines, index)
        elif self.command == "replace":
            [self.begin, self.end] = [int(x) - 1 for x in parts[2].split("-")]
            self.indent = int(parts[3])
            self.filename = parts[4]
            self.contents = getSnippetContents(lines, index)
        elif self.command == "stage-copy":
            self.inputFilename = parts[2]
            self.outputFilename = parts[3]
        elif self.command == "insert-before":
            self.lineno = int(parts[2]) - 1
            self.indent = int(parts[3])
            self.filename = parts[4]
            self.contents = getSnippetContents(lines, index)
        elif self.command == "assert-equals":
            self.filename = parts[2]
            self.contents = getSnippetContents(lines, index)
        # Copy directory in output directory into new directory in output directory
        elif self.command == "output-copy-dir":
            self.cpfrom = parts[2]
            self.cpto = parts[3]
        else:
            sys.stderr.write("Error. Malformed command: %s\n" % lines[index])
            sys.exit(1)

    def run(self, stagingDir, outputDir):
        if self.command == "new":
            self.runNew(outputDir)
        elif self.command == "replace":
            self.runReplace(outputDir)
        elif self.command ==  "stage-copy":
            self.runStageCopy(stagingDir, outputDir)
        elif self.command == "insert-before":
            self.runInsertBefore(outputDir)
        elif self.command == "assert-equals":
            self.runAssertEquals(outputDir)
        elif self.command == "output-copy-dir":
            self.runOutputCopy(outputDir)
        else:
            sys.stderr.write("Error. Malformed command in run: %s\n" % self.command)
            sys.exit(1)

    def mkdirs(self, outputDir, filename):
        dirPath = os.path.join(outputDir, os.path.dirname(filename))
        try:
            os.makedirs(dirPath)
        except OSError as e:
            if e.errno != errno.EEXIST:
                raise 
        return os.path.join(outputDir, filename)

    def runNew(self, outputDir):
        filename = self.mkdirs(outputDir, self.filename)
        with open(filename, "w") as f:
            f.write(self.contents)

    def runReplace(self, outputDir):
        filename = os.path.join(outputDir, self.filename)
        with open(filename) as f:
            fileContents = f.readlines()

        newFileContents = fileContents[0:self.begin]
        newFileContents += indent(self.contents, self.indent) + "\n"
        newFileContents += fileContents[self.end:]

        with open(filename, "w") as f:
            f.write("".join(newFileContents))

    def runStageCopy(self, stagingDir, outputDir):
        inFilename = os.path.join(stagingDir, self.inputFilename)
        outFilename = self.mkdirs(outputDir, self.outputFilename)
        copyfile(inFilename, outFilename)

    def runInsertBefore(self, outputDir):
        filename = os.path.join(outputDir, self.filename)
        with open(filename) as f:
            fileContents = f.readlines()

        newFileContents = fileContents[0:self.lineno]
        newFileContents += indent(self.contents, self.indent) + "\n"
        newFileContents += fileContents[self.lineno:]

        with open(filename, "w") as f:
            f.write("".join(newFileContents))

    def runAssertEquals(self, outputDir):
        filename = os.path.join(outputDir, self.filename)
        with open(filename) as f:
            fileStr = f.read()

        if fileStr != self.contents:
            sys.stderr.write("assert-equals failure. %s does not match:\n%s\n" % (filename, self.contents))
            sys.exit(1)

    def runOutputCopy(self, outputDir):
        fromdir = os.path.join(outputDir, self.cpfrom)
        todir = os.path.join(outputDir, self.cpto)
        copytree(fromdir, todir)






def indent(contents, level):
    lines = [" " * level + x for x in contents.splitlines()]
    return "\n".join(lines)




def getSnippetContents(lines, index):
    contents = []
    for i in xrange(index + 2, len(lines)):
        line = lines[i]
        if line.startswith("\end{minted}") or line.startswith("%\end{minted}"):
            return "\n".join(contents)
        contents.append(line.rstrip("\n").lstrip("%"))

def run(stagingDir, inputFile, outputDir):
    if os.path.exists(outputDir):
        sys.stderr.write("Error. Output directory %s already exists.\n" % outputDir)
        sys.exit(1)

    lines = []
    dirname = os.path.dirname(inputFile)
    with open(inputFile) as f:
        fileNames = f.readlines()
        for fname in fileNames:
            filename = os.path.join(dirname, fname)
            with open(filename.strip()) as g:
                lines += g.readlines()

    snippets = parseLines(lines)

    os.mkdir(outputDir)
    for snippet in snippets:
        snippet.run(stagingDir, outputDir)

def parseLines(lines):

    snippets = []

    for index, line in enumerate(lines):
        if line.startswith("%code-quote"):
            snippets.append(Snippet(lines, index))

    return snippets


    # inputs = [x for x in os.listdir(inputDir) if x.endswith(".tex")]
    # if len(inputs) == 0:
    #     sys.stderr.write("Error. Input directory %s does not contain any .tex files.\n" % inputDir)
    #     sys.exit(1)

    # for inputFile in inputs:
    #     filename = os.path.join(inputDir, inputFile)
    #     with open(filename) as f:
    #         contents = f.read()
    #         print contents

    
if __name__ == "__main__":
    parser = argparse.ArgumentParser(description='Turn code quotes into executable code')
    parser.add_argument('staging_dir', help='the name of the directory containging staging files')
    parser.add_argument('input_file', help='the path to a file containing a list of .tex files')
    parser.add_argument('output_dir', help='the name of the directory to create')
    args = parser.parse_args()
    run(args.staging_dir, args.input_file, args.output_dir)
