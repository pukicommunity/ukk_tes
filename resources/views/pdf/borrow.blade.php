<!DOCTYPE html>
<html lang="en">
<head>
    <title>Borrow Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            color: #333;
        }
        h1 {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 20px;
            color: #4A5568; /* Gray-700 */
        }
        p {
            margin-bottom: 15px;
            line-height: 1.5;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ccc;
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #f7fafc; /* Gray-100 */
            color: #4A5568; /* Gray-700 */
        }
        tr:nth-child(even) {
            background-color: #f9f9f9; /* Light gray */
        }
        tr:hover {
            background-color: #f1f1f1; /* Light gray on hover */
        }
    </style>
</head>
<body>
    <h1>{{ $title }}</h1>
    <p>date:{{ $date }}</p>
    <p>Lorem ipsum dolor sit, amet consectetur adipisicing elit. Soluta eos voluptas recusandae, molestiae vero sit numquam voluptatem natus vel. Amet molestiae labore excepturi! At corrupti quidem quas. Accusantium, neque vero!</p>
    <p>Jumlah user: {{ $borrowCount }}</p>
    <table>
        <tr>
            <th>ID</th>
            <th>Title</th>
            <th>Borrower</th>
            <th>Return Date</th>
        </tr>
        @foreach($borrows as $borrow)
        <tr>
            <td>{{ $borrow->id }}</td>
            <td>{{ $borrow->book->title }}</td>
            <td>{{ $borrow->user->name }}</td>
            <td>{{ $borrow->return_date }}</td>
        </tr>
        @endforeach
    </table>
</body>
</html>