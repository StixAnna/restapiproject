<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Music List</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css'])
</head>
<body>
    <a class="redirect-button" href="/upload">Upload Page</a><br>
    <h1>Music List</h1>
    <ul id="musicList">
        <?php
        // Получение списка музыки из базы данных
        $musicList = App\Models\Sound::all();
        foreach ($musicList as $music) {

            // Отображение элементов списка
            echo
            "<li class='music-item'>
                <div class='music-details'>{$music->fname} (" . number_format($music->fsize / 1024, 2) . " KB) - Duration: {$music->fduration} minutes</div>
                <div class='music-actions'>
                    <button onclick=\"downloadMusic('{$music->fpath}', '{$music->fname}')\">Download</button>
                    <button class='deleteBtn' onclick=\"deleteFile({$music->id}, '{$music->fpath}')\">Delete</button>
                </div>
            </li>";
        }
        ?>
    </ul>

    <script>
        function downloadMusic(filePath, fileName) {
            const link = document.createElement('a');
            link.href = filePath;
            link.download = fileName;
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        }

        function deleteFile(id, filePath) {
            fetch(`{{ route('file.destroy', '') }}/${id}`, {
                method: 'DELETE',
                body: JSON.stringify({ id: id, filePath: filePath }),
                headers: {
                    'Content-Type': 'application/json'
                }
            })
            .then(response => {
                if (response.ok) {
                    location.reload(); // Перезагрузка страницы после успешного удаления
                } else {
                    console.error('Error: Unable to delete the file');
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
        }
    </script>
</body>
</html>
