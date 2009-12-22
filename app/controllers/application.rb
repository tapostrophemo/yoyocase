class ApplicationController < ActionController::Base

  helper :all

  # See ActionController::RequestForgeryProtection for details
  # Uncomment the :secret if you're not using the cookie session store
  protect_from_forgery # :secret => 'b43e2888c32d082959d88f9052dec5e5'
  
  helper_method :current_user, :current_user_session, :user_flickr_photos
  filter_parameter_logging :password

  private

  def current_user
    return @current_user if defined?(@current_user)
    @current_user = current_user_session && current_user_session.record
  end

  def current_user_session
    return @current_user_session if defined?(@current_user_session)
    @current_user_session = UserSession.find
  end

  def user_flickr_photos
    require 'flickr_fu'
    flickr = Flickr.new("#{RAILS_ROOT}/config/flickr.yml")
    flickr.photos.search(:user_id => '45462693@N04')#:tags => 'yoyocase')

#    []
  end

end
