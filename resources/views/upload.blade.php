<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Upload</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css'])
</head>
<body>
    <h1>Upload Page</h1>
    <form id="uploadForm" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="file" name="file"
        {{-- accept="image/*,.png,.jpg,.gif,.web" --}}
        accept="audio/*"
        required>
        <button type="submit">Upload</button>
    </form>

    <script>
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
                }
            })
            .catch(error => {
                console.log(error);
                console.error('Error:', error);
            });
        });
    </script>
</body>
</html>
