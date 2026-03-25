<!DOCTYPE html>
<html>
<head>
    <title>Email Preview</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@picocss/pico@2.0.6/css/pico.min.css">
    <style>
        .warning-banner {
            background: #ff6b6b;
            color: white;
            padding: 1rem;
            margin-bottom: 1rem;
            border-radius: 4px;
            text-align: center;
            font-weight: bold;
        }
    </style>
</head>
<body>
<main class="container">
    <div class="warning-banner">
        ⚠️ NON-PRODUCTION ENVIRONMENT ONLY - {{ strtoupper(app()->environment()) }}
    </div>
    
    <h1>Email Preview</h1>
    
    @if(session('status'))
        <div style="background: #51cf66; color: white; padding: 1rem; margin-bottom: 1rem; border-radius: 4px;">
            {{ session('status') }}
        </div>
    @endif
    
    @if($errors->any())
        <div style="background: #ff6b6b; color: white; padding: 1rem; margin-bottom: 1rem; border-radius: 4px;">
            {{ $errors->first() }}
        </div>
    @endif
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
                        <button type="submit">Send to {{ config('email-preview.test_recipient', 'test recipient') }}</button>
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
