<x-app-layout>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Upload</title>
    <link href="https://fonts.bunny.net/css?family=abeezee:400" rel="stylesheet" />
    @vite(['resources/css/app.css'])
</head>
<body>
    <div class="wrapper">
    <h1>Upload Page</h1>
    <form id="uploadForm" class="upload-form-container" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="file" name="file" id="fileInput" accept="audio/*" required><br>
        <label id="fileLabel" for="fileInput">Choose File</label><br>
        <select id='tagchoose' name="stag">
            <option value="phonk">phonk</option>
            <option value="rock">rock</option>
            <option value="classic">classic</option>
        </select><br>

        <button type="submit">Upload</button>
    </form>

    <script>
        const fileInput = document.getElementById('fileInput');
        const fileLabel = document.getElementById('fileLabel');

        fileInput.addEventListener('change', function() {
            if (fileInput.files.length > 0) {
                fileLabel.textContent = fileInput.files[0].name; // Отображение имени выбранного файла
            } else {
                fileLabel.textContent = 'Choose File';
            }
        });

        document.getElementById('uploadForm').addEventListener('submit', function(event) {
            event.preventDefault();
            let formData = new FormData(this);
            let csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            fetch('{{ route('file.upload') }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                },
                body: formData,
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok ' + response.statusText);
                }
                return response.json();
            })
            .then(data => {
                if (data.error) {
                    console.error('Error:', data.error);
                } else {
                    console.log(data);
                    location.reload();
                }
            })
            .catch(error => {
                console.log(error);
                console.error('Error:', error);
            });
        });
    </script>
    </div>
</x-app-layout>
</body>
</html>
