namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SubscriptionPlan;
use Illuminate\Http\Request;

class SubscriptionPlanController extends Controller
{
    public function __construct()
    {
        $this->middleware('jwt.admin')->except('index');
    }

    public function index()
    {
        return SubscriptionPlan::paginate(10);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'icon' => 'nullable|string',
            'caption_limit' => 'required|integer',
        ]);

        $plan = SubscriptionPlan::create($request->only(['name', 'icon', 'caption_limit']));
        return response()->json($plan, 201);
    }
}