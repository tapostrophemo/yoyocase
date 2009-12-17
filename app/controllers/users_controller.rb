class UsersController < ApplicationController

  def new
    @user = User.new
  end

  def create
    @user = User.new(params[:user])
    if @user.save
      flash[:msg] = "Welcome, #{@user.username}!"
      redirect_to root_url
    else
      render :action => "new"
    end
  end

end
