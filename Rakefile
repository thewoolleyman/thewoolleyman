# -*- ruby -*-

require 'rubygems'
require 'fileutils'

task :default => [:clean, :webgen]

desc "Clean webgen generated output"
task :clean do
  FileUtils.rm_rf "output"
  FileUtils.rm_rf "out"
end

desc "Run Webgen to generate website"
task :webgen do
  rm_f "webgen.cache"
  # Use webgen/RedCloth versions from GemInstaller config
  require 'geminstaller'
  GemInstaller.autogem
  ARGV.pop # Remove webgen exec from argv.  Not sure why this is needed...
  load 'webgen'
  FileUtils.cp_r("out/.",".")
  FileUtils.rm_rf("out")
end

desc 'Publish website'
task :publish_website => [:clean, :webgen] do
  system("ssh thewoolleyweb.com 'cd ~/thewoolleyweb.com && svn up && rake webgen'") || raise("Deploy failed:\n#{$RESULT}")
end
# vim: syntax=Ruby
