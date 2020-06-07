<?php
/**
 * Robo tasks - Rural theme
 * 
 * @see http://robo.li/
 */

use Robo\Robo;
use Symfony\Component\Finder\Finder;

class RoboFile extends \Robo\Tasks
{

    /**
     * Package the specific commitish in a zip file for distribution.
     * Commitish can be a commit hash, a tag, or a branch.
     * 
     * @param string $commit Commitish to be packaged
     * @throws \Exception
     * @return \Robo\Result
     */
    function package($commit = 'master') {
        $getCommitResult = 
            $this->taskExec("git rev-parse --quiet --verify $commit")
                ->interactive(false)
                ->run();
        if(!$getCommitResult->wasSuccessful() || $getCommitResult->getMessage() === '') {
            throw new \Exception('The commit requested does not exist');
        }

        $collection = $this->collectionBuilder();

        $workDir = $collection->workDir("build/release.tmp");

        $PROJECT_NAME = 'webtrees-theme-rural';
        $THEME_DIR = 'myartjaub_ruraltheme';
        
        $output_name = "rural-webtrees-$commit";
        $output_name = str_replace('.', '_', str_replace('-v.', '.', $output_name));

        $build_archive_dir = "$workDir/$output_name.build";
        $build_archive_zip = "$build_archive_dir.zip";

        $build_release_path = "build/$output_name.zip";
        
        $buildTasks = $this->collectionBuilder();

        $buildTasksResult = $buildTasks
            ->taskFilesystemStack()
                ->mkdir($workDir)
            ->progressMessage("Starting build of $PROJECT_NAME $commit")
            ->progressMessage("Export git archive at commit $commit")
            ->taskExec("git archive --format=zip --output=$build_archive_zip $commit")
            ->taskExtract($build_archive_zip)
                ->to($build_archive_dir)
            ->taskComposerInstall()
                ->dir($build_archive_dir)
                ->noProgress()
            //->taskNpmInstall() // Does not handle paths with space
            ->taskExec("npm install --no-fund")
                ->dir($build_archive_dir)
            ->taskExec("npm run production")
                ->dir($build_archive_dir)
            ->progressMessage("Build of $PROJECT_NAME $commit completed!")
            ->run();

        // Exit if the build step failed
        if (!$buildTasksResult->wasSuccessful()) {
            return $buildTasksResult;
        }

        $filesToDelete = Finder::create()
            ->in($build_archive_dir)
            ->name('composer*.*')
            ->name('package*.*')
            ->name('webpack*.*');
        
        $collection
            ->progressMessage("Starting packaging of $PROJECT_NAME $commit")
            ->taskDeleteDir("$build_archive_dir/build")
            ->taskDeleteDir("$build_archive_dir/node_modules")
            ->taskDeleteDir("$build_archive_dir/src")
            ->taskDeleteDir("$build_archive_dir/vendor") // This line tends to fail on Windows - Permission denied
            ->taskFilesystemStack()
                ->remove($filesToDelete)
            ->taskPack($build_release_path)
                ->addDir($THEME_DIR, $build_archive_dir)
            ->taskDeleteDir($workDir)
            ->progressMessage("Package created: $build_release_path !!!")
            ;
        
        return $collection->run();
    }
}