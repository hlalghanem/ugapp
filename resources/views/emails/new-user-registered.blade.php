<p>A new user has been registered:</p>
<ul>
    <li>Name: {{ $user->name }}</li>
    <li>Email: {{ $user->email }}</li>
    <li>Company: {{ $user->company }}</li>
    <li>Phone: {{ $user->phone }}</li>
    <li>Registration Date: {{ \Carbon\Carbon::parse($user->created_at)->format('Y-m-d H:i:s') }}
    </li>
</ul>
