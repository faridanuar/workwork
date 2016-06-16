
<!-- This is to check if session has a flash message "key" if not it will return null-->    
@if (session()->has('flash_message'))
	
	<!-- check what kind of flash message level name is set and added to div class--> 
    <div class="Alert Alert--{{ ucwords(session('flash_message_level')) }}">
    
    	<!-- flash message with session -->
        {{ session('flash_message') }}
    </div>

@endif