<p>A new user has been registered:</p>
<ul>
    <li>Name: {{ $user->name }}</li>
    <li>Email: {{ $user->email }}</li>
    <li>Registration Date: {{ \Carbon\Carbon::parse($user->created_at)->addHours(3)->format('Y-m-d H:i:s') }}
    </li>
</ul>
