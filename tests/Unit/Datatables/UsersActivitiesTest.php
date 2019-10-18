<?php
/**Copyright (c) 2018-2019 Qualcomm Technologies, Inc.
All rights reserved.
Redistribution and use in source and binary forms, with or without modification, are permitted (subject to the limitations in the disclaimer below) provided that the following conditions are met:
Redistributions of source code must retain the above copyright notice, this list of conditions and the following disclaimer.
Redistributions in binary form must reproduce the above copyright notice, this list of conditions and the following disclaimer in the documentation and/or other materials provided with the distribution.
Neither the name of Qualcomm Technologies, Inc. nor the names of its contributors may be used to endorse or promote products derived from this software without specific prior written permission.
The origin of this software must not be misrepresented; you must not claim that you wrote the original software. If you use this software in a product, an acknowledgment is required by displaying the trademark/log as per the details provided here: https://www.qualcomm.com/documents/dirbs-logo-and-brand-guidelines
Altered source versions must be plainly marked as such, and must not be misrepresented as being the original software.
This notice may not be removed or altered from any source distribution.
NO EXPRESS OR IMPLIED LICENSES TO ANY PARTY'S PATENT RIGHTS ARE GRANTED BY THIS LICENSE. THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT HOLDER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.*/
namespace Tests\Unit\Datatables;

use App\IMEI;
use App\Role;
use App\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use Tests\app\BasicTestSuiteSetup;
use Tests\TestCase;

class UsersActivitiesTest extends TestCase {
	use MockeryPHPUnitIntegration;
	use RefreshDatabase;
	use BasicTestSuiteSetup;
	protected $superadminrole;
	protected $superadmin;
	protected $admin;
	protected $staff;

	public function setUp() {
		parent::setUp();
		DB::beginTransaction();

		factory( Role::class )->create( [
			'slug' => 'superadmin'
		] );
		$super_admin_role = Role::where( 'slug', 'superadmin' )->first();

		$this->superadmin = factory( User::class )->create( [
			'first_name' => 'dcp super',
			'last_name'  => 'super-admin',
			'email'      => 'super@3gca.org',
			'password'   => bcrypt( 'Admin@1234' ),
			'active'     => true,
			'loginCount' => 1,
			'agreement'  => 'Agreed'
		] );

		$this->superadmin->roles()->attach( $super_admin_role );


		//Admin
		factory( Role::class )->create( [
			'slug' => 'admin'
		] );
		$admin_role = Role::where( 'slug', 'admin' )->first();

		$this->admin = factory( User::class )->create( [
			'first_name' => 'dcpadmin',
			'last_name'  => 'admin',
			'email'      => 'admin@3gca.org',
			'password'   => bcrypt( 'Admin@1234' ),
			'active'     => true,
			'loginCount' => 1,
			'agreement'  => 'Agreed'
		] );

		$this->admin->roles()->attach( $admin_role );

		//Staff
		factory( Role::class )->create( [
			'slug' => 'staff'
		] );
		$staff_role = Role::where( 'slug', 'staff' )->first();

		$this->staff = factory( User::class )->create( [
			'first_name' => 'test',
			'last_name'  => 'staff',
			'email'      => 'staff@3gca.org',
			'password'   => bcrypt( 'Staff@1234' ),
			'active'     => true,
			'loginCount' => 1,
			'agreement'  => 'Agreed'
		] );

		$this->staff->roles()->attach( $staff_role );
		DB::commit();

	}

	/**
	 * @test
	 * Super admin can view users activities
	 */
	public function superAdminCanViewAllUsersActivities() {


		$token = \JWTAuth::fromUser( $this->superadmin );

		$response = $this->get( '/api/datatable/users-activity?token=' . $token );

		$response->assertStatus( 200 );
		$users_activity = IMEI::orderBy( 'id', 'desc' )->paginate( 10 )->toArray();

		$response->assertJsonStructure( [
			'activity'
		] );
		$response->assertJson( [
			'activity' => $users_activity
		] );
		$response->assertSuccessful();
		$response->assertJsonCount( 1 );
	}

	/**
	 * @test
	 * Super admin can view users activities
	 */
	public function superAdminCanViewAllUsersActivitiesIfLangIsVi() {

		$token    = \JWTAuth::fromUser( $this->superadmin );
		$response = $this->get( '/api/datatable/users-activity?token=' . $token, [
			'x-localization' => 'vi'
		] );

		$response->assertStatus( 200 );
		$users_activity = IMEI::orderBy( 'id', 'desc' )->paginate( 10 )->toArray();

		$vi_data       = $users_activity['data'];
		$vi_activities = collect( $vi_data )->map( function ( $item ) {
			$item['result']          = 'không hợp lệ';
			$item['user_device']     = 'Ứng dụng web';
			$item['checking_method'] = 'Tìm kiếm thủ công';

			return $item;
		} );

		$vi_col = ( new Collection( $vi_activities ) )->paginate( 10 )->toArray();
		$response->assertJsonStructure( [
			'activity'
		] );
		$response->assertJson( [
			'activity' => $vi_col
		] );
		$response->assertSuccessful();
		$response->assertJsonCount( 1 );


	}

	/**
	 * @test
	 * Admin can view all user activities
	 */
	public function AdminCanViewAllUsersActivities() {
		$token = \JWTAuth::fromUser( $this->admin );

		$response = $this->get( '/api/datatable/users-activity?token=' . $token );

		$response->assertStatus( 200 );
		$users_activity = IMEI::orderBy( 'id', 'desc' )->paginate( 10 )->toArray();

		$response->assertJsonStructure( [
			'activity'
		] );
		$response->assertJson( [
			'activity' => $users_activity
		] );
		$response->assertSuccessful();
		$response->assertJsonCount( 1 );
	}

	/**
	 * @test
	 * Super admin can view users activities
	 */
	public function AdminCanViewAllUsersActivitiesIfLangIsVi() {

		$token    = \JWTAuth::fromUser( $this->admin );
		$response = $this->get( '/api/datatable/users-activity?token=' . $token, [
			'x-localization' => 'vi'
		] );

		$response->assertStatus( 200 );
		$users_activity = IMEI::orderBy( 'id', 'desc' )->paginate( 10 )->toArray();

		$vi_data       = $users_activity['data'];
		$vi_activities = collect( $vi_data )->map( function ( $item ) {
			$item['result']          = 'không hợp lệ';
			$item['user_device']     = 'Ứng dụng web';
			$item['checking_method'] = 'Tìm kiếm thủ công';

			return $item;
		} );

		$vi_col = ( new Collection( $vi_activities ) )->paginate( 10 )->toArray();
		$response->assertJsonStructure( [
			'activity'
		] );
		$response->assertJson( [
			'activity' => $vi_col
		] );
		$response->assertSuccessful();
		$response->assertJsonCount( 1 );


	}

	/**
	 * @test
	 * Staff canNOT view all users activities
	 */
	public function StaffCannotViewAllUsersActivities() {
		$token = \JWTAuth::fromUser( $this->staff );

		$this->get( '/api/datatable/users-activity?token=' . $token )
		     ->assertStatus( 403 )
		     ->assertJsonStructure( [
			     'error',
			     'message'
		     ] )->assertExactJson( [
				'error'   => true,
				'message' => 'You do not have enough permissions for this request, please contact system administrator for more details'
			] )
		     ->assertJsonCount( 2 );
	}

	/**
	 * @test
	 * A valid token is required for all users actiivties
	 */
	public function aTokenIsRequiredforViewAllUsersActivities() {

		$this->get( '/api/datatable/users-activity' )
		     ->assertStatus( 401 )
		     ->assertJson( [
			     'errors' => [
				     'root' => 'Token is missing'
			     ]
		     ] )
		     ->assertJsonCount( 1 );
	}

	/**
	 * @test
	 * A valid token is required for all users actiivties
	 */
	public function aValidTokenIsRequiredforViewAllUsersActivities() {

		$this->get( '/api/datatable/users-activity?token=lorempisumtoken' )
		     ->assertStatus( 401 )
		     ->assertJson( [
			     'errors' => [
				     'root' => 'Token is invalid'
			     ]
		     ] )
		     ->assertJsonCount( 1 );
	}


}
