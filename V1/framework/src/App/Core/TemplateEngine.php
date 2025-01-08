<?php

/*
* MIT License
*
* Copyright (c) 2024 Atrylix
*
* Permission is hereby granted, free of charge, to any person obtaining a copy
* of this software and associated documentation files (the "Software"), to deal
* in the Software without restriction, including without limitation the rights
* to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
* copies of the Software, and to permit persons to whom the Software is
* furnished to do so, subject to the following conditions:
*
* The above copyright notice and this permission notice shall be included in all
* copies or substantial portions of the Software.
*
* THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
* IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
* FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
* AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
* LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
* OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
* SOFTWARE.
*/

namespace App\Core;

use App\Handlers\ErrorHandler;
use App\Handlers\Utilities;
use Exception;

class TemplateEngine
{
    // Singleton instance of the template engine
    private static $instance;

    // Path to the template folder
    private $templateFolder = __DIR__ . '/../Views/';

    // Include paths
    private $includesFolder;
    private $includes = [];

    /**
     * Initializes the template engine singleton
     * @return TemplateEngine
     */
    public static function init()
    {
        if (self::$instance == null) {
            self::$instance = new self();
            return self::$instance;
        } else {
            return self::$instance;
        }
    }

    private function __construct()
    {
        $this->includesFolder = $this->templateFolder . 'includes/';
        $this->includes = $this->loadIncludes();
    }

    public function render($template, ...$data)
    {
        $data = Utilities::flattenArray($data);

        $templatePath = $this->templateFolder . $template;

        if ($this->templateExists($template)) {
            echo $this->parseTemplate($templatePath, $data);
        } else {
            echo 'template ' . "'$templatePath'" . ' does not exist';
        }
    }

    private function templateExists($template)
    {
        $templatePath = $this->templateFolder . $template;

        if (empty($templatePath)) {
            throw new Exception('Template path not given');
        }

        return file_exists($templatePath);
    }

    private function loadIncludes()
    {
        $includeFile = scandir($this->includesFolder);
        $includes = [];

        foreach ($includeFile as $file) {
            if ($file != '.' && $file != '..') {
                $filePath = $this->includesFolder . $file;
                $includes[] = array(
                    'includeName' => $file,
                    'includePath' => $filePath
                );
            }
        }

        return $includes;
    }

    private function parseTemplate($templatePath, $data)
    {
        $templateContent = file_get_contents($templatePath);

        $templateContent = $this->populateTemplateWithIncludes($templateContent);
        $templateContent = $this->replaceTemplateVariables($templateContent, $data);

        return $templateContent;
    }

    private function replaceTemplateVariables($templateContent, $data)
    {        
        foreach ($data as $key => $value) {
            $pattern = '/<<var ' . preg_quote($key, '/') . '>>/';
            $templateContent = preg_replace($pattern, $value, $templateContent);
        }

        $pattern = '/<<var (.*?)>>/';
        $templateContent = preg_replace($pattern, '!!!Variable not found!!!', $templateContent);

        return $templateContent;
    }

    private function populateTemplateWithIncludes($templateContent)
    {
        foreach ($this->includes as $includeArray) {
            $includeContent = file_get_contents($includeArray['includePath']);
            $pattern = '/<<include ' . preg_quote($includeArray['includeName'], '/') . '>>/';
            $templateContent = preg_replace($pattern, $includeContent, $templateContent);
        }

        $pattern = '/<<include (.*?)>>/';
        $templateContent = preg_replace($pattern, '!!!Include not found!!!', $templateContent);

        return $templateContent;
    }
}
