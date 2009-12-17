class UserSessionsController < ApplicationController

  def new
    @user_session = UserSession.new
  end

  def create
    @user_session = UserSession.new(params[:user_session])
    if @user_session.save
      flash[:msg] = "Welcome back, #{@user_session.user.username}!"
      redirect_to account_url
    else
      render :action => 'new'
    end
  end

  def destroy
    current_user_session.destroy
    redirect_to root_url
  end

end
