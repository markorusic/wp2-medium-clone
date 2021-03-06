<div>
  <table class="table">
    <thead>
      <tr>
        <th>Activity</th>
        <th>Time</th>
      </tr>
    </thead>
    <tbody>
      @foreach ($activities as $activity)
        <tr>
          <td>
            {{ $activity->type }}
            @if ($activity->description)
                <span class="text-success"> {{ $activity->description }}</span>
            @endif
          </td>
          <td>{{ $activity->created_at->format('M d, Y H:i:s')  }}</td>
        </tr>
      @endforeach
    </tbody>
  </table>
  
  {{ $activities->links() }}
</div>