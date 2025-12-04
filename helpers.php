<?php

if (!function_exists('resolverImagemLivro')) {
    /**
     * Retorna o caminho da imagem para um livro, aplicando substituições específicas
     * e caindo para um placeholder quando necessário.
     */
    function resolverImagemLivro(array $livro): string
    {
        $defaultImage = 'assets/img/default-book.jpg';
        $titulo = $livro['titulo'] ?? '';
        $imagem = $livro['imagem'] ?? '';

        $overrides = [
            'O Pequeno Príncipe' => 'assets/img/o-pequeno-principe.jpg',
            'A Culpa é das Estrelas' => 'assets/img/culpa.jpg',
        ];
        
        // Mapeamento por autor para imagens específicas
        $autorImagens = [
            'Nathan' => 'assets/img/nathan.jpg',
            'Hugo' => 'assets/img/hugo.jpg',
            'Mendonça' => 'assets/img/mendonca.jpg',
        ];
        
        $autor = $livro['autor'] ?? '';
        if (!empty($autor)) {
            foreach ($autorImagens as $nomeAutor => $imagemPath) {
                if (stripos($autor, $nomeAutor) !== false && resolverImagemExiste($imagemPath)) {
                    return $imagemPath;
                }
            }
        }

        if (!empty($titulo) && isset($overrides[$titulo])) {
            $overridePath = $overrides[$titulo];
            if (resolverImagemExiste($overridePath)) {
                return $overridePath;
            }
        }

        if (!empty($imagem) && resolverImagemExiste($imagem)) {
            return $imagem;
        }

        return $defaultImage;
    }
}

if (!function_exists('resolverImagemExiste')) {
    /**
     * Verifica se um arquivo existe a partir de um caminho relativo ao projeto.
     */
    function resolverImagemExiste(string $relativePath): bool
    {
        $rootPath = dirname(__DIR__);
        $absolutePath = $rootPath . DIRECTORY_SEPARATOR . str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $relativePath);

        return file_exists($absolutePath);
    }
}
?>

