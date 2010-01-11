module NavigationHelpers
  # Maps a name to a path. Used by the
  #
  #   When /^I go to (.+)$/ do |page_name|
  #
  # step definition in webrat_steps.rb
  #
  def path_to(page_name)
    case page_name

    when /the home\s?page/
      '/'

    when /my account page/
      '/account'

    when /flickr auth/
      'http://flickr.com/services/auth/'

    when /Edit yoyo (\d+)/
      edit_yoyo_path($1)

    when /Remove photo (\d+)/
      url_for :controller => 'yoyos', :action => 'remove_photo', :id => $1

#    when /flickr callback path/
# TODO: the frob seems to change each time; this was an old one, effective on 12/2x/2009?...
#      '/users/update_flickr_info_2?frob=72157623082248800-8b816863d77667ba-933518'
# ...and the following worked on 12/31/2009; how am I going to test this?
#      '/users/update_flickr_info_2?frob=72157623106139166-da9b98cb7bb92f20-871459'

    # Add more mappings here.
    # Here is a more fancy example:
    #
    #   when /^(.*)'s profile page$/i
    #     user_profile_path(User.find_by_login($1))

    else
      raise "Can't find mapping from \"#{page_name}\" to a path.\n" +
        "Now, go and add a mapping in #{__FILE__}"
    end
  end
end

World(NavigationHelpers)
