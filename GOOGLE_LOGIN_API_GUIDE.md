# دليل الحصول على Google Access Token للـ API

## 📱 نظرة عامة

للاستخدام مع `/api/v1/auth/google`، تحتاج إلى الحصول على `access_token` من جوجل. الطريقة تختلف حسب المنصة (Android, iOS, Web, React Native).

---

## 🌐 Web (JavaScript/React/Vue)

### الطريقة 1: استخدام Google Identity Services (الطريقة الحديثة)

```html
<!DOCTYPE html>
<html>
<head>
    <script src="https://accounts.google.com/gsi/client" async defer></script>
</head>
<body>
    <div id="g_id_onload"
         data-client_id="YOUR_GOOGLE_CLIENT_ID"
         data-callback="handleCredentialResponse">
    </div>
    <div class="g_id_signin" data-type="standard"></div>

    <script>
        function handleCredentialResponse(response) {
            // response.credential هو ID Token، نحتاج Access Token
            // للحصول على Access Token، استخدم Google OAuth 2.0
            getAccessToken();
        }

        async function getAccessToken() {
            const client = google.accounts.oauth2.initTokenClient({
                client_id: 'YOUR_GOOGLE_CLIENT_ID',
                scope: 'email profile',
                callback: (response) => {
                    // response.access_token هو ما تحتاجه
                    console.log('Access Token:', response.access_token);
                    
                    // أرسل إلى API
                    loginWithGoogle(response.access_token);
                },
            });
            client.requestAccessToken();
        }

        async function loginWithGoogle(accessToken) {
            const response = await fetch('https://endak.net/api/v1/auth/google', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                },
                body: JSON.stringify({
                    access_token: accessToken,
                    device_token: 'optional_device_token'
                })
            });

            const data = await response.json();
            console.log('Login Result:', data);
            
            if (data.success) {
                // حفظ الـ token
                localStorage.setItem('api_token', data.data.token);
                // توجيه المستخدم
                window.location.href = '/dashboard';
            }
        }
    </script>
</body>
</html>
```

### الطريقة 2: استخدام React (مثال)

```jsx
import { useEffect } from 'react';

function GoogleLoginButton() {
    useEffect(() => {
        // تحميل Google Identity Services
        const script = document.createElement('script');
        script.src = 'https://accounts.google.com/gsi/client';
        script.async = true;
        script.defer = true;
        document.body.appendChild(script);

        return () => {
            document.body.removeChild(script);
        };
    }, []);

    const handleGoogleLogin = () => {
        const client = window.google.accounts.oauth2.initTokenClient({
            client_id: 'YOUR_GOOGLE_CLIENT_ID',
            scope: 'email profile',
            callback: async (response) => {
                try {
                    const apiResponse = await fetch('https://endak.net/api/v1/auth/google', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                        },
                        body: JSON.stringify({
                            access_token: response.access_token,
                        }),
                    });

                    const data = await apiResponse.json();
                    
                    if (data.success) {
                        localStorage.setItem('api_token', data.data.token);
                        // توجيه المستخدم
                        window.location.href = '/dashboard';
                    } else {
                        alert('فشل تسجيل الدخول: ' + data.message);
                    }
                } catch (error) {
                    console.error('Error:', error);
                    alert('حدث خطأ أثناء تسجيل الدخول');
                }
            },
        });

        client.requestAccessToken();
    };

    return (
        <button onClick={handleGoogleLogin}>
            تسجيل الدخول بجوجل
        </button>
    );
}

export default GoogleLoginButton;
```

---

## 📱 Android (Kotlin/Java)

### إضافة Dependencies

في `build.gradle` (Module: app):

```gradle
dependencies {
    implementation 'com.google.android.gms:play-services-auth:20.7.0'
}
```

### الكود (Kotlin)

```kotlin
import com.google.android.gms.auth.api.signin.GoogleSignIn
import com.google.android.gms.auth.api.signin.GoogleSignInAccount
import com.google.android.gms.auth.api.signin.GoogleSignInClient
import com.google.android.gms.auth.api.signin.GoogleSignInOptions
import com.google.android.gms.common.api.ApiException
import com.google.android.gms.tasks.Task

class LoginActivity : AppCompatActivity() {
    private lateinit var googleSignInClient: GoogleSignInClient
    private val RC_SIGN_IN = 9001

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        
        // إعداد Google Sign-In
        val gso = GoogleSignInOptions.Builder(GoogleSignInOptions.DEFAULT_SIGN_IN)
            .requestEmail()
            .requestProfile()
            .requestIdToken("YOUR_GOOGLE_CLIENT_ID") // من Google Cloud Console
            .build()

        googleSignInClient = GoogleSignIn.getClient(this, gso)
        
        // زر تسجيل الدخول
        findViewById<Button>(R.id.googleSignInButton).setOnClickListener {
            signInWithGoogle()
        }
    }

    private fun signInWithGoogle() {
        val signInIntent = googleSignInClient.signInIntent
        startActivityForResult(signInIntent, RC_SIGN_IN)
    }

    override fun onActivityResult(requestCode: Int, resultCode: Int, data: Intent?) {
        super.onActivityResult(requestCode, resultCode, data)

        if (requestCode == RC_SIGN_IN) {
            val task = GoogleSignIn.getSignedInAccountFromIntent(data)
            handleSignInResult(task)
        }
    }

    private fun handleSignInResult(completedTask: Task<GoogleSignInAccount>) {
        try {
            val account = completedTask.getResult(ApiException::class.java)
            
            // الحصول على Access Token
            account?.idToken?.let { idToken ->
                // للحصول على Access Token، نحتاج إلى استخدام GoogleAuthUtil
                getAccessToken(account)
            }
        } catch (e: ApiException) {
            Log.e("GoogleSignIn", "signInResult:failed code=" + e.statusCode)
        }
    }

    private fun getAccessToken(account: GoogleSignInAccount) {
        // في Android، عادة ما نحصل على ID Token
        // يمكن استخدام ID Token مباشرة أو تحويله إلى Access Token
        
        // الطريقة المباشرة: استخدام ID Token (إذا كان API يدعمه)
        // أو استخدام GoogleAuthUtil للحصول على Access Token
        
        val scope = "oauth2:https://www.googleapis.com/auth/userinfo.email https://www.googleapis.com/auth/userinfo.profile"
        
        Thread {
            try {
                val accessToken = GoogleAuthUtil.getToken(
                    this,
                    account.email!!,
                    scope
                )
                
                // أرسل إلى API
                runOnUiThread {
                    loginWithGoogleAPI(accessToken)
                }
            } catch (e: Exception) {
                Log.e("GoogleAuth", "Error getting access token", e)
            }
        }.start()
    }

    private fun loginWithGoogleAPI(accessToken: String) {
        val retrofit = Retrofit.Builder()
            .baseUrl("https://endak.net/api/v1/")
            .addConverterFactory(GsonConverterFactory.create())
            .build()

        val apiService = retrofit.create(ApiService::class.java)
        
        val request = GoogleLoginRequest(
            access_token = accessToken,
            device_token = getDeviceToken() // FCM token
        )

        apiService.googleLogin(request).enqueue(object : Callback<LoginResponse> {
            override fun onResponse(call: Call<LoginResponse>, response: Response<LoginResponse>) {
                if (response.isSuccessful && response.body()?.success == true) {
                    val token = response.body()?.data?.token
                    // حفظ الـ token
                    saveApiToken(token)
                    // توجيه المستخدم
                    startActivity(Intent(this@LoginActivity, MainActivity::class.java))
                    finish()
                } else {
                    Toast.makeText(this@LoginActivity, "فشل تسجيل الدخول", Toast.LENGTH_SHORT).show()
                }
            }

            override fun onFailure(call: Call<LoginResponse>, t: Throwable) {
                Toast.makeText(this@LoginActivity, "خطأ في الاتصال", Toast.LENGTH_SHORT).show()
            }
        })
    }
}
```

### API Interface (Retrofit)

```kotlin
interface ApiService {
    @POST("auth/google")
    fun googleLogin(@Body request: GoogleLoginRequest): Call<LoginResponse>
}

data class GoogleLoginRequest(
    val access_token: String,
    val device_token: String? = null
)

data class LoginResponse(
    val success: Boolean,
    val message: String,
    val data: LoginData?
)

data class LoginData(
    val token: String,
    val user: User,
    val is_new_user: Boolean,
    val needs_profile_completion: Boolean
)
```

---

## 🍎 iOS (Swift)

### إضافة Google Sign-In SDK

في `Podfile`:

```ruby
pod 'GoogleSignIn'
```

ثم `pod install`

### الكود (Swift)

```swift
import UIKit
import GoogleSignIn

class LoginViewController: UIViewController {
    
    override func viewDidLoad() {
        super.viewDidLoad()
        
        // إعداد Google Sign-In
        guard let path = Bundle.main.path(forResource: "GoogleService-Info", ofType: "plist"),
              let plist = NSDictionary(contentsOfFile: path),
              let clientId = plist["CLIENT_ID"] as? String else {
            print("Error: Could not load GoogleService-Info.plist")
            return
        }
        
        let config = GIDConfiguration(clientID: clientId)
        GIDSignIn.sharedInstance.configuration = config
        GIDSignIn.sharedInstance.delegate = self
        
        // زر تسجيل الدخول
        googleSignInButton.addTarget(self, action: #selector(signInWithGoogle), for: .touchUpInside)
    }
    
    @objc func signInWithGoogle() {
        GIDSignIn.sharedInstance.signIn(withPresenting: self) { [weak self] result, error in
            guard let self = self else { return }
            
            if let error = error {
                print("Error: \(error.localizedDescription)")
                return
            }
            
            guard let user = result?.user,
                  let idToken = user.idToken?.tokenString else {
                print("Error: Could not get ID token")
                return
            }
            
            // الحصول على Access Token
            user.refreshTokensIfNeeded { user, error in
                guard let accessToken = user?.accessToken.tokenString else {
                    print("Error: Could not get access token")
                    return
                }
                
                // أرسل إلى API
                self.loginWithGoogleAPI(accessToken: accessToken)
            }
        }
    }
    
    func loginWithGoogleAPI(accessToken: String) {
        let url = URL(string: "https://endak.net/api/v1/auth/google")!
        var request = URLRequest(url: url)
        request.httpMethod = "POST"
        request.setValue("application/json", forHTTPHeaderField: "Content-Type")
        request.setValue("application/json", forHTTPHeaderField: "Accept")
        
        let body: [String: Any] = [
            "access_token": accessToken,
            "device_token": getDeviceToken() // FCM token
        ]
        
        request.httpBody = try? JSONSerialization.data(withJSONObject: body)
        
        URLSession.shared.dataTask(with: request) { data, response, error in
            guard let data = data,
                  let json = try? JSONSerialization.jsonObject(with: data) as? [String: Any],
                  let success = json["success"] as? Bool,
                  success == true,
                  let dataDict = json["data"] as? [String: Any],
                  let token = dataDict["token"] as? String else {
                print("Error: Invalid response")
                return
            }
            
            // حفظ الـ token
            UserDefaults.standard.set(token, forKey: "api_token")
            
            // توجيه المستخدم
            DispatchQueue.main.async {
                let storyboard = UIStoryboard(name: "Main", bundle: nil)
                let mainVC = storyboard.instantiateViewController(withIdentifier: "MainViewController")
                self.navigationController?.setViewControllers([mainVC], animated: true)
            }
        }.resume()
    }
}

extension LoginViewController: GIDSignInDelegate {
    func sign(_ signIn: GIDSignIn!, didSignInFor user: GIDGoogleUser!, withError error: Error!) {
        // تم التعامل معه في signInWithGoogle
    }
}
```

---

## ⚛️ React Native

### تثبيت المكتبة

```bash
npm install @react-native-google-signin/google-signin
# أو
yarn add @react-native-google-signin/google-signin
```

### الكود

```javascript
import { GoogleSignin } from '@react-native-google-signin/google-signin';
import { useState } from 'react';

GoogleSignin.configure({
  webClientId: 'YOUR_GOOGLE_CLIENT_ID', // من Google Cloud Console
  offlineAccess: true, // للحصول على refresh token
});

function LoginScreen() {
  const [loading, setLoading] = useState(false);

  const signInWithGoogle = async () => {
    try {
      setLoading(true);
      
      // تسجيل الدخول
      await GoogleSignin.hasPlayServices();
      const userInfo = await GoogleSignin.signIn();
      
      // الحصول على Access Token
      const tokens = await GoogleSignin.getTokens();
      const accessToken = tokens.accessToken;
      
      // أرسل إلى API
      const response = await fetch('https://endak.net/api/v1/auth/google', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json',
        },
        body: JSON.stringify({
          access_token: accessToken,
          device_token: await getDeviceToken(), // FCM token
        }),
      });
      
      const data = await response.json();
      
      if (data.success) {
        // حفظ الـ token
        await AsyncStorage.setItem('api_token', data.data.token);
        
        // توجيه المستخدم
        navigation.navigate('Home');
      } else {
        Alert.alert('خطأ', data.message || 'فشل تسجيل الدخول');
      }
    } catch (error) {
      console.error('Google Sign-In Error:', error);
      Alert.alert('خطأ', 'فشل تسجيل الدخول بجوجل');
    } finally {
      setLoading(false);
    }
  };

  return (
    <View>
      <Button
        title="تسجيل الدخول بجوجل"
        onPress={signInWithGoogle}
        disabled={loading}
      />
    </View>
  );
}
```

---

## 🦋 Flutter (Dart)

### تثبيت المكتبة

في `pubspec.yaml`:

```yaml
dependencies:
  flutter:
    sdk: flutter
  google_sign_in: ^6.2.1
  http: ^1.1.0
```

ثم قم بتشغيل:
```bash
flutter pub get
```

### إعداد Android

1. في `android/app/build.gradle`، تأكد من أن `minSdkVersion` هو 21 أو أعلى:
```gradle
android {
    defaultConfig {
        minSdkVersion 21
    }
}
```

2. احصل على SHA-1 certificate fingerprint:
```bash
# للتطوير
keytool -list -v -keystore ~/.android/debug.keystore -alias androiddebugkey -storepass android -keypass android

# للإنتاج
keytool -list -v -keystore /path/to/your/keystore.jks -alias your-key-alias
```

3. أضف SHA-1 في [Google Cloud Console](https://console.cloud.google.com/) → APIs & Services → Credentials → OAuth 2.0 Client ID (Android)

### إعداد iOS

1. في `ios/Runner/Info.plist`، أضف:
```xml
<key>CFBundleURLTypes</key>
<array>
    <dict>
        <key>CFBundleTypeRole</key>
        <string>Editor</string>
        <key>CFBundleURLSchemes</key>
        <array>
            <string>YOUR_REVERSED_CLIENT_ID</string>
        </array>
    </dict>
</array>
```

2. احصل على `REVERSED_CLIENT_ID` من ملف `GoogleService-Info.plist` الذي تحمله من Google Cloud Console

### الكود (Flutter/Dart)

```dart
import 'package:flutter/material.dart';
import 'package:google_sign_in/google_sign_in.dart';
import 'package:http/http.dart' as http;
import 'dart:convert';

class LoginScreen extends StatefulWidget {
  @override
  _LoginScreenState createState() => _LoginScreenState();
}

class _LoginScreenState extends State<LoginScreen> {
  final GoogleSignIn _googleSignIn = GoogleSignIn(
    scopes: ['email', 'profile'],
  );

  bool _isLoading = false;

  Future<void> _signInWithGoogle() async {
    try {
      setState(() {
        _isLoading = true;
      });

      // تسجيل الدخول بجوجل
      final GoogleSignInAccount? googleUser = await _googleSignIn.signIn();
      
      if (googleUser == null) {
        // المستخدم ألغى تسجيل الدخول
        setState(() {
          _isLoading = false;
        });
        return;
      }

      // الحصول على Authentication object
      final GoogleSignInAuthentication googleAuth = await googleUser.authentication;

      // الحصول على Access Token
      final String? accessToken = googleAuth.accessToken;

      if (accessToken == null) {
        throw Exception('فشل الحصول على Access Token من جوجل');
      }

      // أرسل إلى API
      await _loginWithGoogleAPI(accessToken);

    } catch (error) {
      print('Google Sign-In Error: $error');
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(
          content: Text('فشل تسجيل الدخول بجوجل: $error'),
          backgroundColor: Colors.red,
        ),
      );
    } finally {
      setState(() {
        _isLoading = false;
      });
    }
  }

  Future<void> _loginWithGoogleAPI(String accessToken) async {
    try {
      final response = await http.post(
        Uri.parse('https://endak.net/api/v1/auth/google'),
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json',
        },
        body: jsonEncode({
          'access_token': accessToken,
          'device_token': await _getDeviceToken(), // FCM token (اختياري)
        }),
      );

      final data = jsonDecode(response.body);

      if (data['success'] == true) {
        // حفظ الـ token
        final apiToken = data['data']['token'];
        await _saveApiToken(apiToken);

        // توجيه المستخدم
        Navigator.of(context).pushReplacementNamed('/home');
        
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(
            content: Text(data['message'] ?? 'تم تسجيل الدخول بنجاح'),
            backgroundColor: Colors.green,
          ),
        );
      } else {
        throw Exception(data['message'] ?? 'فشل تسجيل الدخول');
      }
    } catch (error) {
      print('API Error: $error');
      throw Exception('خطأ في الاتصال بالخادم: $error');
    }
  }

  Future<String?> _getDeviceToken() async {
    // احصل على FCM token هنا
    // مثال: await FirebaseMessaging.instance.getToken();
    return null;
  }

  Future<void> _saveApiToken(String token) async {
    // احفظ الـ token باستخدام SharedPreferences أو أي طريقة أخرى
    // مثال:
    // final prefs = await SharedPreferences.getInstance();
    // await prefs.setString('api_token', token);
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: Text('تسجيل الدخول'),
      ),
      body: Center(
        child: Padding(
          padding: EdgeInsets.all(16.0),
          child: Column(
            mainAxisAlignment: MainAxisAlignment.center,
            children: [
              ElevatedButton.icon(
                onPressed: _isLoading ? null : _signInWithGoogle,
                icon: _isLoading
                    ? SizedBox(
                        width: 20,
                        height: 20,
                        child: CircularProgressIndicator(
                          strokeWidth: 2,
                          valueColor: AlwaysStoppedAnimation<Color>(Colors.white),
                        ),
                      )
                    : Image.network(
                        'https://www.gstatic.com/firebasejs/ui/2.0.0/images/auth/google.svg',
                        height: 20,
                      ),
                label: Text(_isLoading ? 'جاري تسجيل الدخول...' : 'تسجيل الدخول بجوجل'),
                style: ElevatedButton.styleFrom(
                  padding: EdgeInsets.symmetric(horizontal: 24, vertical: 12),
                ),
              ),
            ],
          ),
        ),
      ),
    );
  }
}
```

### استخدام SharedPreferences لحفظ Token

أضف في `pubspec.yaml`:
```yaml
dependencies:
  shared_preferences: ^2.2.2
```

ثم في الكود:

```dart
import 'package:shared_preferences/shared_preferences.dart';

Future<void> _saveApiToken(String token) async {
  final prefs = await SharedPreferences.getInstance();
  await prefs.setString('api_token', token);
}

Future<String?> _getApiToken() async {
  final prefs = await SharedPreferences.getInstance();
  return prefs.getString('api_token');
}
```

### استخدام Token في API Requests

```dart
import 'package:http/http.dart' as http;
import 'dart:convert';
import 'package:shared_preferences/shared_preferences.dart';

class ApiService {
  static Future<Map<String, String>> _getHeaders() async {
    final prefs = await SharedPreferences.getInstance();
    final token = prefs.getString('api_token');
    
    return {
      'Content-Type': 'application/json',
      'Accept': 'application/json',
      if (token != null) 'Authorization': 'Bearer $token',
    };
  }

  static Future<Map<String, dynamic>> getProfile() async {
    final response = await http.get(
      Uri.parse('https://endak.net/api/v1/auth/profile'),
      headers: await _getHeaders(),
    );

    if (response.statusCode == 200) {
      return jsonDecode(response.body);
    } else {
      throw Exception('فشل تحميل الملف الشخصي');
    }
  }
}
```

### تسجيل الخروج

```dart
Future<void> _signOut() async {
  try {
    // تسجيل الخروج من جوجل
    await _googleSignIn.signOut();
    
    // حذف API token
    final prefs = await SharedPreferences.getInstance();
    await prefs.remove('api_token');
    
    // تسجيل الخروج من API
    final token = await prefs.getString('api_token');
    if (token != null) {
      await http.post(
        Uri.parse('https://endak.net/api/v1/auth/logout'),
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json',
          'Authorization': 'Bearer $token',
        },
      );
    }
    
    // توجيه إلى صفحة تسجيل الدخول
    Navigator.of(context).pushReplacementNamed('/login');
  } catch (error) {
    print('Sign out error: $error');
  }
}
```

### مثال كامل مع State Management (Provider)

```dart
// auth_provider.dart
import 'package:flutter/foundation.dart';
import 'package:google_sign_in/google_sign_in.dart';
import 'package:http/http.dart' as http;
import 'dart:convert';
import 'package:shared_preferences/shared_preferences.dart';

class AuthProvider with ChangeNotifier {
  final GoogleSignIn _googleSignIn = GoogleSignIn(
    scopes: ['email', 'profile'],
  );

  bool _isLoading = false;
  String? _apiToken;
  Map<String, dynamic>? _user;

  bool get isLoading => _isLoading;
  String? get apiToken => _apiToken;
  Map<String, dynamic>? get user => _user;
  bool get isAuthenticated => _apiToken != null;

  Future<void> signInWithGoogle() async {
    try {
      _isLoading = true;
      notifyListeners();

      final GoogleSignInAccount? googleUser = await _googleSignIn.signIn();
      if (googleUser == null) {
        _isLoading = false;
        notifyListeners();
        return;
      }

      final GoogleSignInAuthentication googleAuth = await googleUser.authentication;
      final String? accessToken = googleAuth.accessToken;

      if (accessToken == null) {
        throw Exception('فشل الحصول على Access Token');
      }

      await _loginWithAPI(accessToken);
    } catch (error) {
      print('Sign in error: $error');
      rethrow;
    } finally {
      _isLoading = false;
      notifyListeners();
    }
  }

  Future<void> _loginWithAPI(String accessToken) async {
    final response = await http.post(
      Uri.parse('https://endak.net/api/v1/auth/google'),
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
      },
      body: jsonEncode({
        'access_token': accessToken,
      }),
    );

    final data = jsonDecode(response.body);

    if (data['success'] == true) {
      _apiToken = data['data']['token'];
      _user = data['data']['user'];
      
      final prefs = await SharedPreferences.getInstance();
      await prefs.setString('api_token', _apiToken!);
      
      notifyListeners();
    } else {
      throw Exception(data['message'] ?? 'فشل تسجيل الدخول');
    }
  }

  Future<void> loadToken() async {
    final prefs = await SharedPreferences.getInstance();
    _apiToken = prefs.getString('api_token');
    notifyListeners();
  }

  Future<void> signOut() async {
    await _googleSignIn.signOut();
    
    final prefs = await SharedPreferences.getInstance();
    await prefs.remove('api_token');
    
    _apiToken = null;
    _user = null;
    notifyListeners();
  }
}
```

### استخدام Provider في App

```dart
// main.dart
import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import 'auth_provider.dart';

void main() {
  runApp(MyApp());
}

class MyApp extends StatelessWidget {
  @override
  Widget build(BuildContext context) {
    return ChangeNotifierProvider(
      create: (_) => AuthProvider()..loadToken(),
      child: MaterialApp(
        title: 'Endak App',
        home: AuthWrapper(),
      ),
    );
  }
}

class AuthWrapper extends StatelessWidget {
  @override
  Widget build(BuildContext context) {
    final authProvider = Provider.of<AuthProvider>(context);
    
    if (authProvider.isLoading) {
      return Scaffold(body: Center(child: CircularProgressIndicator()));
    }
    
    return authProvider.isAuthenticated
        ? HomeScreen()
        : LoginScreen();
  }
}
```

---

## 🔑 الحصول على Google Client ID

1. اذهب إلى [Google Cloud Console](https://console.cloud.google.com/)
2. اختر المشروع أو أنشئ مشروع جديد
3. اذهب إلى **APIs & Services** → **Credentials**
4. اضغط **Create Credentials** → **OAuth client ID**
5. اختر نوع التطبيق:
   - **Web application** للويب
   - **Android** للأندرويد (تحتاج SHA-1 certificate fingerprint)
   - **iOS** للآي أو إس (تحتاج Bundle ID)
6. انسخ **Client ID** واستخدمه في الكود

---

## 📝 ملاحظات مهمة

1. **Access Token vs ID Token**:
   - **ID Token**: يحتوي على معلومات المستخدم (JWT)
   - **Access Token**: يستخدم للوصول إلى Google APIs
   - الـ API الحالي يستخدم **Access Token**

2. **Device Token**:
   - اختياري
   - يستخدم للإشعارات (FCM/APNS)
   - يمكن إرساله لاحقاً عبر `/api/v1/auth/profile`

3. **Security**:
   - لا تشارك `access_token` مع أي شخص
   - الـ token ينتهي صلاحيته بعد فترة
   - استخدم HTTPS دائماً

---

## 🧪 اختبار سريع (Postman)

إذا كنت تريد اختبار الـ API مباشرة:

1. احصل على `access_token` من Google OAuth Playground:
   - اذهب إلى: https://developers.google.com/oauthplayground/
   - اختر **Google OAuth2 API v2**
   - اختر scope: `https://www.googleapis.com/auth/userinfo.email` و `https://www.googleapis.com/auth/userinfo.profile`
   - اضغط **Authorize APIs**
   - سجل دخولك بجوجل
   - اضغط **Exchange authorization code for tokens**
   - انسخ **Access token**

2. استخدمه في Postman:
   ```json
   POST https://endak.net/api/v1/auth/google
   Content-Type: application/json
   
   {
     "access_token": "ya29.a0AfH6SMB..."
   }
   ```

---

## 📚 روابط مفيدة

- [Google Sign-In Documentation](https://developers.google.com/identity/sign-in/web/sign-in)
- [Google OAuth 2.0](https://developers.google.com/identity/protocols/oauth2)
- [Google Cloud Console](https://console.cloud.google.com/)
