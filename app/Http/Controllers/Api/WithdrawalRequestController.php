namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\WithdrawalRequest;
use Illuminate\Http\Request;

class WithdrawalRequestController extends Controller
{
    public function __construct()
    {
        $this->middleware('jwt.admin')->except('index');
    }

    public function index()
    {
        return WithdrawalRequest::with('user')->paginate(10);
    }

    public function store(Request $request)
    {
        $request->validate([
            'subscription_name' => 'required|string',
            'user_id' => 'required|exists:users,id',
        ]);

        $request = WithdrawalRequest::create($request->only(['subscription_name', 'user_id']));
        return response()->json($request, 201);
    }
}