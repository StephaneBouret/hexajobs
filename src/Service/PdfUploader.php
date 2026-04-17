<?php

declare(strict_types=1);

namespace App\Service;

final class PdfUploader
{
    private const MAX_BYTES = 2 * 1024 * 1024; // 2 Mo

    public function __construct(
        private readonly string $uploadDir = CV_UPLOAD_DIR,
        private readonly string $publicPrefix = CV_PUBLIC_PREFIX
    ) {
        $dir = rtrim($this->uploadDir, '/\\');
        // Crée le dossier s'il n'existe pas
        if (!is_dir($dir)) {
            mkdir($dir, 0775, true);
        }
    }

    public function upload(string $field, string $baseName, ?string $existing = null): string
    {
        // 1) Aucun fichier envoyé => on garde l'existant
        if (!isset($_FILES[$field]) || ($_FILES[$field]['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE) {
            return $existing ?? '';
        }

        $file = $_FILES[$field];

        // 2) Erreur d'upload (PHP)
        if (($file['error'] ?? UPLOAD_ERR_OK) !== UPLOAD_ERR_OK) {
            throw new \RuntimeException("Erreur upload (code " . (int)$file['error'] . ").");
        }

        // 3) Limite de taille : 2 Mo
        if (($file['size'] ?? 0) > self::MAX_BYTES) {
            throw new \RuntimeException("Fichier trop lourd : 2 Mo maximum.");
        }

        $tmpPath = (string) ($file['tmp_name'] ?? '');

        if ($tmpPath === '' || !is_uploaded_file($tmpPath)) {
            throw new \RuntimeException("Fichier uploadé invalide.");
        }

        $mime = (string) mime_content_type($tmpPath);

        $allowedMimes = [
            'application/pdf' => 'pdf',
            // prêt pour plus tard 
            // 'application/msword' => 'doc',
            // 'application/vnd.openxmlformats-officedocument.wordprocessingml.document' => 'docx',
        ];

        if (!array_key_exists($mime, $allowedMimes)) {
            throw new \RuntimeException("Format non supporté. Seuls les fichiers PDF sont autorisés.");
        }

        $extension = $allowedMimes[$mime];

        // 5) Génère un nom de fichier "slug-0001.pdf"
        $slug = self::slugify($baseName . '-cv');
        $filename = $this->nextNumberedFilename($slug, $extension);
        $target = rtrim($this->uploadDir, '/\\') . DIRECTORY_SEPARATOR . $filename;

        if (!move_uploaded_file($tmpPath, $target)) {
            throw new \RuntimeException("Impossible d'enregistrer le fichier.");
        }

        // 6) Supprime un fichier PDF (sécurisé : basename)
        if ($existing) {
            $this->delete($existing);
        }

        return $filename;
    }

    /**
     * Supprime un fichier PDF (sécurisé : basename)
     */
    public function delete(?string $filename): void
    {
        if (!$filename) return;
        $path = rtrim($this->uploadDir, '/\\') . DIRECTORY_SEPARATOR . basename($filename);
        if (is_file($path)) {
            @unlink($path);
        }
    }

    /**
     * URL publique d'un document (utile dans les vues)
     */
    public function getPublicUrl(?string $filename): ?string
    {
        if (!$filename) return null;
        return rtrim($this->publicPrefix, '/') . '/' . ltrim(basename($filename), '/');
    }

    private function nextNumberedFilename(string $slug, string $extension): string
    {
        $dir = rtrim($this->uploadDir, '/\\');
        $files = glob($dir . DIRECTORY_SEPARATOR . $slug . '-*.' . $extension) ?: [];
        $max = 0;
        foreach ($files as $path) {
            $base = basename($path);
            if (preg_match('/^' . preg_quote($slug, '/') . '-(\d{4})\.' . preg_quote($extension, '/') . '$/', $base, $m)) {
                $n = (int) $m[1];
                if ($n > $max) $max = $n;
            }
        }
        return sprintf('%s-%04d.%s', $slug, $max + 1, $extension);
    }

    /**
     * Transforme un titre en slug 
     */
    public static function slugify(string $text): string
    {
        $text = trim($text);
        $text = mb_strtolower($text, 'UTF-8');
        // translit (é -> e) si possible
        $text = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $text) ?: $text;
        $text = preg_replace('~[^a-z0-9]+~', '-', $text) ?? '';
        $text = trim($text, '-');
        return $text !== '' ? $text : 'cv';
    }
}
