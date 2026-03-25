<!DOCTYPE html>
<html>
<head>
    <title>Email Preview</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@picocss/pico@2.0.6/css/pico.min.css">
</head>
<body>
<main class="container">
    <h1>Email Preview</h1>
    <table>
        <thead>
            <tr>
                <th>Email</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        @forelse($previews as $type => $preview)
            <tr>
                <td><strong>{{ $preview['label'] ?? $type }}</strong></td>
                <td>
                    <a href="{{ route('email-preview.show', $type) }}" target="_blank" class="contrast">Preview</a>
                    <form action="{{ route('email-preview.send', $type) }}" method="POST" style="display:inline">
                        @csrf
                        <input type="hidden" name="to" value="{{ config('email-preview.test_recipient') }}">
                        <button type="submit">Send</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr><td colspan="2">No emails configured.</td></tr>
        @endforelse
        </tbody>
    </table>
</main>
</body>
</html>
