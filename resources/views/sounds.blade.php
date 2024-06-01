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
    <ul id="musicList"></ul>

    <script>
        // URL вашего API
        const apiUrl = '{{ route('sounds.show') }}';

        // Функция для получения списка музыки
        async function fetchMusicList() {
            try {
                const response = await fetch(apiUrl);
                if (!response.ok) {
                    throw new Error('Network response was not ok ' + response.statusText);
                }
                const data = await response.json();
                displayMusicList(data);
            } catch (error) {
                console.error('Error:', error);
            }
        }

        // Функция для отображения списка музыки
        function displayMusicList(musicList) {
            const musicListElement = document.getElementById('musicList');
            musicListElement.innerHTML = ''; // Очистка предыдущего списка

            // Создание HTML-элементов для каждого музыкального файла
            musicList.forEach(music => {
                const listItem = createListItem(music);
                musicListElement.appendChild(listItem);
            });
        }

        // Создание элемента списка для музыки
        function createListItem(music) {
            const listItem = document.createElement('li');
            listItem.className = 'music-item';

            const musicDetails = createMusicDetailsElement(music);
            const musicActions = createMusicActionsElement(music);

            listItem.appendChild(musicDetails);
            listItem.appendChild(musicActions);

            return listItem;
        }

        // Создание элемента с информацией о музыке
        function createMusicDetailsElement(music) {
            const musicDetails = document.createElement('div');
            musicDetails.className = 'music-details';
            musicDetails.textContent = `${music.fname} (${(music.fsize / 1024).toFixed(2)} KB) - Path: ${music.fpath}`;
            return musicDetails;
        }

        // Создание элемента с действиями для музыки (кнопки Download и Delete)
        function createMusicActionsElement(music) {
            const musicActions = document.createElement('div');
            musicActions.className = 'music-actions';

            const downloadButton = createButton('Download', () => {
                downloadMusic(music.fpath, music.fname);
            });

            const deleteButton = createButton('Delete', () => {
                deleteFile(music);
            });
            deleteButton.style.marginLeft = '10px';
            deleteButton.className = 'deleteBtn';

            musicActions.appendChild(downloadButton);
            musicActions.appendChild(deleteButton);

            return musicActions;
        }

        // Создание кнопки
        function createButton(text, onClick) {
            const button = document.createElement('button');
            button.textContent = text;
            button.addEventListener('click', onClick);
            return button;
        }

        // Функция для загрузки музыки
        function downloadMusic(filePath, fileName) {
            const link = document.createElement('a');
            link.href = filePath;
            link.download = fileName; // Использование переданного имени файла для загрузки
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        }

        // Функция для удаления файла
        function deleteFile(music) {
            fetch('{{ route('file.destroy', '') }}/' + music.id, {

                method: 'DELETE',
                body: JSON.stringify({ 'id': music.id, 'filePath': music.fpath }), // Send file path to server
                headers: {
                    'Content-Type': 'application/json'
                }
            })
            .then(response => {
                if (response.ok) {
                    // File deleted successfully
                    console.log('File deleted successfully');
                    fetchMusicList(); // Refresh the music list
                } else {
                    // Error occurred while deleting the file
                    console.error('Error: Unable to delete the file');
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
        }

        // Вызов функции для получения и отображения списка при загрузке страницы
        document.addEventListener('DOMContentLoaded', fetchMusicList);
    </script>
</body>
</html>
