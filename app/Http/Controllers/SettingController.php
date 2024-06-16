<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Str;

use Storage;
use Carbon\Carbon;
use App\Models\AdmissionDB\User;
use App\Models\ScheduleDB\Faculty;

use App\Models\SettingDB\ConfigureCurrent;
use App\Models\SettingDB\GradePass;

class SettingController extends Controller
{
    public function getGuard()
    {
        if(\Auth::guard('web')->check()) {
            return 'web';
        } elseif(\Auth::guard('faculty')->check()) {
            return 'faculty';
        }
    }

    public function index()
    {
        return view('control.settings.index');
    }

    public function usersRead() 
    {
        $data = User::all();
        return view('control.settings.users.listusers', compact('data'));
    }

    public function userCreate(Request $request) 
    {

        if ($request->isMethod('post')) {
            $request->validate([
                'lname' => 'required',
                'fname' => 'required',
                'mname' => 'required',
                'email' => 'required|string|email|max:255',
                'password' => 'required|string|min:5',
                'dept' => 'required',
                'isAdmin' => 'required',
                'campus' => 'required',
            ]);

            $emailName = $request->input('email'); 
            $existingEmail = User::where('email', $emailName)->first();

            if ($existingEmail) {
                return redirect()->route('usersRead')->with('error', 'User already exists!');
            }

            try {
                User::create([
                    'lname' => $request->input('lname'),
                    'fname' => $request->input('fname'),
                    'mname' => $request->input('mname'),
                    'email' => $emailName,
                    'password' => Hash::make($request->input('password')),
                    'dept' => $request->input('dept'),
                    'isAdmin' => $request->input('isAdmin'),
                    'campus' => $request->input('campus'),
                    'remember_token' => Str::random(60),
                ]);

                return redirect()->route('usersRead')->with('success', 'User stored successfully!');
            } catch (\Exception $e) {
                return redirect()->route('usersRead')->with('fail', 'Failed to store user!');
            }
        }
    }

    public function accountRead() 
    {
        $guard= $this->getGuard();
        $user = Auth::guard($guard)->user(); 
        return view('control.settings.users.account', compact('user', 'guard'));
    }

    public function edit_user($id) 
    {
        $userID = decrypt($id);
        $user = User::find($userID);

        return view('control.settings.users.editUser', compact('user'));
    }

    public function updateUser(Request $request)
    {
        $request->validate([
            'isAdmin' => 'required',
            'campus' => 'required',
            'email' => 'required|string|email|max:255|unique:users,email,' . $request->input('id'),
            'lname' => 'required|string|max:255',
            'fname' => 'required|string|max:255',
            'isAdmin' => 'required',
        ]);

        try {
            $user = User::findOrFail($request->input('id'));
            
            $emailName = $request->input('email');
            $existingEmail = User::where('email', $emailName)->where('id', '!=', $request->input('id'))->first();

            if ($existingEmail) {
                return redirect()->back()->with('fail', 'User already exists!');
            }

            $user->update([
                'campus' => $request->input('campus'),
                'dept' => $request->input('dept'),
                'lname' => $request->input('lname'),
                'fname' => $request->input('fname'),
                'mname' => $request->input('mname'),
                'ext' => $request->input('ext'),
                'email' => $emailName,
                'isAdmin' => $request->input('isAdmin'),
            ]);

            return redirect()->route('edit_user', ['id' => encrypt($user->id)])->with('success', 'User Updated Successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('fail', 'Failed to update User!');
        }
    }

    public function userUpdatePassword(Request $request) {
        $user = User::find($request->id);
        
        $request->validate([
            'id' => 'required',
            'password' => 'required|string|min:5',
        ]);

        try {
            $user = User::findOrFail($request->input('id'));
            $user->update([
                'password' => Hash::make($request->input('password'))
            ]);

            return redirect()->back()->with(['id' => encrypt($user->id), 'success' => 'Password Updated Successfully']);
        } catch (\Exception $e) {
            return redirect()->back()->with('fail', 'Failed to update User Password!');
        }
    }

    public function setconfigure() 
    {
        return view('control.settings.current.cur_config');
    }

    public function getsetconfigure() 
    {
        $data = ConfigureCurrent::orderBy('id', 'DESC')->get();
        return response()->json(['data' => $data]);
    }

    public function setconfCreate(Request $request) 
    {
        if ($request->isMethod('post')) {
            $request->validate([
                'schlyear' => 'required',
                'semester' => 'required',
            ]);

            $schlyearName = $request->input('schlyear'); 
            $semesterName = $request->input('semester'); 

            $existingConfiguration = ConfigureCurrent::where('schlyear', $schlyearName)
                ->where('semester', $semesterName)
                ->first();

            if ($existingConfiguration) {
                return response()->json(['error' => true, 'message' => 'School Year and Semester already exists'], 404);
            }

            try {
                ConfigureCurrent::create([
                    'schlyear' => $schlyearName,
                    'semester' => $semesterName,
                ]);
                return response()->json(['success' => true, 'message' => 'School Year and Semester added successfully!'], 200);
            } catch (\Exception $e) {
                return response()->json(['error' => true, 'message' => 'Failed to store School Year and Semester!']);
            }
        }
    }

    public function setgradepassconfigure() 
    {
        $gradepasttngs = GradePass::all();
        return view('control.settings.current.gradepass_config', compact('gradepasttngs'));
    }

    public function setconfUpdate(Request $request) 
    {
        $request->validate([
            'id' => 'required',
            'schlyear' => 'required',
            'semester' => 'required',
            'set_status' => 'required',
        ]);

        $schlyear = $request->input('schlyear');
        $semester = $request->input('semester');
        $set_status = $request->input('set_status');

        try {
            $setconfName = $request->input('schlyear'); 
            $setconfSem = $request->input('semester'); 
            $existingSetConf = ConfigureCurrent::where('schlyear', $setconfName)
                            ->where('schlyear', $setconfSem)
                            ->where('id', '!=', $request->input('id'))->first();

            if ($existingSetConf) {
                return response()->json(['error' => true, 'message' => 'School Year already exists'], 404);
            }

            $setconf = ConfigureCurrent::findOrFail($request->input('id'));
            $setconf->update([
                'schlyear' => $request->input('schlyear'),
                'semester' => $request->input('semester'),
                'set_status' => $request->input('set_status'),
        ]);
            return response()->json(['success' => true, 'message' => 'School Year update successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => true, 'message' => 'Failed to store School Year'], 404);
        }
    }

    public function setgradepassconfCreate(Request $request) 
    {
        if ($request->isMethod('post')) {
            $request->validate([
                'gradeauthpass' => 'required',
            ]);

            $gradeauthpassName = $request->input('gradeauthpass');  

            $existingGradePass = GradePass::where('gradeauthpass', $gradeauthpassName)
                ->first();

            if ($existingGradePass) {
                return redirect()->route('setgradepassconfigure')->with('fail', 'Grade Password already exists!');
            }

            try {
                GradePass::create([
                    'gradeauthpass' => $gradeauthpassName,
                ]);

                return redirect()->route('setgradepassconfigure')->with('success', 'Grade Password added successfully!');
            } catch (\Exception $e) {
                return redirect()->route('setgradepassconfigure')->with('error', 'Failed to store Grade Password!');
            }
        }
    }

    public function updateGradepass(Request $request, $id)
    {
        $validatedData = $request->validate([
            'gradeauthpass' => 'required|max:255',
        ]);

        $grade = GradePass::find($id);

        if (!$grade) {
            return redirect()->back()->with('fail', 'Grade Password not found');
        }

        $grade->gradeauthpass = $request->input('gradeauthpass');

        $grade->save();

        return redirect()->back()->with('success', 'Grades Password updated successfully');
    }

    public function serverMaintenance()
    {
        return view('control.settings.admin.settingsMaintenance',  [
            'maintenance_mode' => Config::get('settings.maintenance_mode', false),
        ]);
    }

    public function toggleMaintenance(Request $request)
    {
        $maintenanceMode = $request->input('maintenance_mode') === 'on';
        
        // Update the config file or database
        // Example for config file (config/settings.php):
        $configFile = config_path('settings.php');
        $config = require $configFile;
        $config['maintenance_mode'] = $maintenanceMode;
        file_put_contents($configFile, '<?php return ' . var_export($config, true) . ';');

        return redirect()->back()->with('success', 'Maintenance mode updated!');
    }

}
