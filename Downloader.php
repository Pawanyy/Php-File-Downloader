<?php

class Downloader
{
    protected string $filePath;
    private array $AllowedExt;
    private array $fileTypes;


    public function __construct()
    {
    }

    public function setAllowedExt(array $allowedExt): Downloader
    {
        $this->AllowedExt = $allowedExt;
        return $this;
    }

    public function setFileTypes(array $fileTypes): Downloader
    {
        $this->fileTypes = $fileTypes;
        return $this;
    }

    public function getDefaultFileTypes(): array
    {
        return  [
            'text/plain', 'application/x-empty', 'application/json',
            'application/zip', 'application/pdf', 'application/sql',
            'application/xml', 'audio/mpeg', 'audio/ogg',
            'image/jpeg', 'image/png', 'image/avif', 'image/svg+xml',
            'text/css', 'text/csv', 'text/xml',
        ];
    }

    public function getDefaultExt(): array
    {
        return  [
            'txt', 'json', 'zip', 'pdf',
            'sql', 'mpeg', 'ogg', 'jpeg',
            'png', 'avif', 'svg', 'css',
            'csv', 'xml',
        ];
    }

    public function SetPath(string $filePath): Downloader
    {
        $this->filePath = $filePath;
        return $this;
    }

    public function Download(string $fileName): void
    {
        //Check the file exists or not
        if(file_exists($this->filePath)) {

            $fileInfoMime  = finfo_open(FILEINFO_MIME_TYPE);
            $fileInfo      = finfo_file($fileInfoMime, $this->filePath);
            $fileExt       = strtolower(pathinfo($this->filePath, PATHINFO_EXTENSION));

            $this->ValidateFile($fileExt, $fileInfo);

            $this->TransferFile($fileName);

            //Terminate from the script
            //die();

        } else {

            echo "File does not exist.";

        }
    }

    public function TransferFile(string $fileName): void
    {
        //Define header information
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header("Cache-Control: no-cache, must-revalidate");
        header("Expires: 0");
        header('Content-Disposition: attachment; filename="' . basename($fileName) . '"');
        header('Content-Length: ' . filesize($this->filePath));
        header('Pragma: public');

        //Clear system output buffer
        flush();

        //Read the size of the file
        readfile($this->filePath);
    }

    public function ValidateFile(string $fileExt, bool|string $fileInfo): void
    {
        if (empty($this->fileTypes)) {
            $this->fileTypes = $this->getDefaultFileTypes();
        }

        if (empty($this->AllowedExt)) {
            $this->AllowedExt = $this->getDefaultExt();
        }

        if (!in_array($fileExt, $this->AllowedExt)) {
            die('Wrong types Ext!');
        }

        if (!in_array($fileInfo, $this->fileTypes)) {
            die('Wrong types Mime!');
        }
    }

    public function DirectDownload(string $fileName): void
    {
        //Check the file exists or not
        if(file_exists($this->filePath)) {

            $this->TransferFile($fileName);

            //Terminate from the script
            //die();

        } else {

            echo "File does not exist.";

        }
    }

}