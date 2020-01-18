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
          <td>{{ $activity->type . ' ' . $activity->description ?? '' }}</td>
          <td>{{ $activity->created_at->format('M d, Y H:i:s')  }}</td>
        </tr>
      @endforeach
    </tbody>
  </table>
  
  {{ $activities->links() }}
</div>