<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>English</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css">
  </head>
  <body>
    <br>
    <div class="container">
    <h2>{{ ucfirst($studentname )}}'s Daily Practices</h2>
    <hr>
    @if (session('success'))
<div class="alert alert-success">
    {{ session('success') }}
</div>
@endif
@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
    <form method="POST" action="{{ route('myenglishdatastore') }}">
        @csrf
        <input type="hidden" name="student" value="{{ ucfirst($studentname) }}">
        <div>
            <label class="form-label" for="ondate">Date:</label>
            <input type="date" class="form-control" id="ondate" name="ondate" value="{{ date('Y-m-d') }}">
        </div>
        
        <div>
            <label class="form-label" for="inminutes">Minutes:</label>
            <input type="text" class="form-control" name="inminutes" id="inminutes" pattern="[0-9]*" inputmode="tel"  required min="1" max="180">
        </div>
        <button type="submit" class="btn btn-outline-info m-2">Submit</button>
    </form>
    <p><b>Today: {{ $today }}</b> </p>
    <p><i> Days#{{ $distinctDateCount }} - Avg: {{ $avg_in_time_string }} Daily</i></p>
   <p><i>Total: {{ $total }} </i></p> 

   
   <div class="card mb-1">
    <div class="card-header">
      <a class="collapsed btn" data-bs-toggle="collapse" href="#collapseTwo">
        <b> Last 20 Entries ▼</b>
    </a>
    </div>
    <div id="collapseTwo" class="collapse" data-bs-parent="#accordion">
      <div class="card-body">
        
    @if ($entries->count() > 0)
    <table class="table">
        <thead>
            <tr>
                <th>Date</th>
                <th>Minutes</th>
                <th>Created At</th>
                {{-- <th>Student</th> --}}
                <th></th>
            </tr>
        </thead>
        <tbody>
            @foreach ($entries as $entry)
                <tr>
                    <td>{{ \Carbon\Carbon::createFromFormat('Y-m-d', $entry->ondate)->format('d-M') }}</td>
                    <td>{{ $entry->inminutes }}</td>
                    <td>{{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $entry->created_at)->format('d-M H:i') }}</td>
                    {{-- <td>{{ $entry->student }}</td> --}}
                    <td>
                        @if(strtotime($entry->created_at) > strtotime('-180 minutes')) 
                        <form method="POST" action="{{ route('myenglishdatadelete', ['id' => $entry->id]) }}">
                            @csrf
                            @method('delete')
                            <button type="submit" title="delete" class="btn btn-outline-danger btn-sm "><i class="bi bi-trash3-fill"></i></button>
                        </form>
                        @endif

                        
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@else
    <p>No entries found.</p>
@endif


      </div>
    </div>
  </div>
  <div class="card mb-1">
    <div class="card-header">
      <a class="collapsed btn" data-bs-toggle="collapse" href="#collapse3">
        <b>All Days ▼</b>
    </a>
    </div>
    <div id="collapse3" class="collapse" data-bs-parent="#accordion">
      <div class="card-body">
        @if ($totalperday->count() > 0)
        <table class="table">
            <thead>
                <tr>
                    <th>Day</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($totalperday as $day)
                    <tr>
                        <td>{{ \Carbon\Carbon::createFromFormat('Y-m-d', $day->ondate)->format('D d-M') }}</td>
                        <td>
                           

<!-- Example Usage -->
@php
    $minutes =$day->total_minutes; // Replace with your actual value
@endphp

{{ formatTime($minutes) }}
                           
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p>No entries found.</p>
    @endif
      </div>
    </div>
  </div>

    <br>
   

    {{-- Function to change minutes to text --}}
    @php
    function formatTime($minutes) {
        $hours = floor($minutes / 60);
        $remainingMinutes = $minutes % 60;

        $timeString = '';

        if ($hours > 0) {
            $timeString .= $hours . ' hour';
            if ($hours > 1) {
                $timeString .= 's';
            }
        }

        if ($remainingMinutes > 0) {
            if ($hours > 0) {
                $timeString .= ' and ';
            }

            $timeString .= $remainingMinutes . ' minute';
            if ($remainingMinutes > 1) {
                $timeString .= 's';
            }
        }

        return $timeString;
    }
@endphp

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
</div>  
</body>
</html>

