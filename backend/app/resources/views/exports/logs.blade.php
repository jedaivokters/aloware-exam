<table>
    <thead>
    <tr>
        @foreach($questionnaireLabels as $ql)
            <th>{{$ql['label']}}</th>
        @endforeach
    </tr>
    </thead>
    <tbody>
    @foreach($logs as $key => $log)
        <tr>
            <td>{{ $key + 1 }}</td>
            <td>{{ $log['date'] }}</td>
            <td>{{ $log['time'] }}</td>
            <td>{{ $log['last_name'] }}</td>
            <td>{{ $log['first_name'] }}</td>
            <td>{{ $log['reservation_no'] }}</td>
            <td>{{ $log['phone'] }}</td>
            <td>{{ $log['address'] }}</td>
            <td>{{ $log['email'] }}</td>
            <td>{{ $log['age'] }}</td>
            <td>{{ $log['dob'] }}</td>
            <td>{{ $log['gender'] }}</td>
            <td>{{ $log['nationality'] }}</td>
            <td>{{ $log['occupancy'] }}</td>
            <td>{{ $log['gov_id'] }}</td>
            <td>{{ $log['gov_id_number'] }}</td>
            <td>{{ $log['body_temperature'] }}</td>
            <td>{{ $log['coughing'] }}</td>
            <td>{{ $log['doctor_certificate'] }}</td>
            <td>{{ $log['pass_affection'] }}</td>
            <td>{{ $log['prev_destination'] }}</td>
            <td>{{ $log['next_destination'] }}</td>
            <td>{{ $log['transportation'] }}</td>
            <td>{{ $log['internation_travel'] }}</td>
            <td>{{ $log['domestic_travel'] }}</td>
            <td>{{ $log['foreign_travel'] }}</td>
            <td>{{ $log['close_contact_corona_patient'] }}</td>
            <td>{{ $log['relationship_with_main_visitor'] }}</td>
            <td>{{ $log['terms_conditions'] }}</td>
            <td>{{ $log['main_visitor'] }}</td>

            @foreach($extras as $key => $val)
                <td>{{ $log[$key] }}</td>
            @endforeach

            @for($ctr = 1; $ctr <= 20; $ctr ++)
                <td>{{ $log['optional_question_'. $ctr] }}</td>
            @endfor
        </tr>
    @endforeach
    </tbody>
</table>