class UsersController < ApplicationController

  def new
    @user = User.new
  end

  def create
    @user = User.new(params[:user])
    if @user.save
      flash[:msg] = "Welcome, #{@user.username}!"
      redirect_to account_url
    else
      render :action => "new"
    end
  end

  def edit
    @user = current_user
  end

  def update
    @user = current_user
    if @user.update_attributes(params[:user])
      flash[:msg] = "Preferences updated successfully"
      redirect_to account_url
    else
      render :action => "edit"
    end
  end

  def update_flickr_info_1
    require 'flickr_fu'
    flickr = Flickr.new("#{RAILS_ROOT}/config/flickr.yml")
    redirect_to flickr.auth.url(:read)
  end

  def update_flickr_info_2
    require 'flickr_fu'
    flickr = Flickr.new("#{RAILS_ROOT}/config/flickr.yml")
    flickr.auth.frob = params[:frob]
    current_user.update_attribute :flickr_userid, flickr.auth.token.user_id
    redirect_to account_url
  end

end
